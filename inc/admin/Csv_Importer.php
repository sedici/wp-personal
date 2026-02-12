<?php

namespace Personal\Inc\Admin;

class Csv_Importer
{

    protected $csv_file;

    protected int $num_columns_expected;
    protected array $cpts_to_create;
    protected array $cpts_to_update;

    protected array $errors;

    protected $csv_config; // Parametros para pasarle a la funcion fopen y parsear el csv

    protected array $rules;

    public function __construct($csv_file)
    {
        $this->num_columns_expected = 13;
        $this->csv_file = $csv_file;
        $this->rules = $this->initialize_rules();

        $this->errors = [];
        $this->cpts_to_create = [];
        $this->cpts_to_update = [];
    }


    protected function initialize_rules()
    {
        $rules = [
            'email' => [
                'required' => true,
                'sanitize' => 'sanitize_email',
                'validate' => function ($value) {
                    return !empty($value);
                },
                'error' => 'El formato del correo electrónico no es válido.'
            ],
            'nombre_apellido' => [
                'required' => false,
                'sanitize' => 'sanitize_text_field',
                'validate' => function ($value) {
                    return true;
                },
                'error' => ''
            ],
            'telefono' => [
                'required' => false,
                'sanitize' => 'sanitize_text_field',
                'validate' => function ($valor) {
                    // Permite números, espacios, guiones, paréntesis y el signo +
                    return preg_match('/^[\d\+\-\(\)\s]+$/', $valor);
                },
                'error' => 'El teléfono contiene caracteres no permitidos.'
            ],
            'unidad_de_investigacion' => [
                'required' => false,
                'sanitize' => 'sanitize_text_field',
                'validate' => function ($valor) {
                    return true;
                },
                'error' => 'La unidad de investigación es demasiado corta.'
            ],

            'rol_unidad_de_investigacion' => [
                'required' => false,
                'sanitize' => 'sanitize_text_field',
                'validate' => function ($valor) {
                    return true;
                },
                'error' => ''
            ],
            'grado_alcanzado' => [
                'required' => false,
                'sanitize' => 'sanitize_text_field',
                'validate' => function ($valor) {
                    return true;
                },
                'error' => ''
            ],

            'google_scholar' => [
                'required' => false,
                'sanitize' => 'esc_url_raw',
                'validate' => function ($valor) {
                    return true;
                },
                'error' => ''
            ],
            'orcid' => [
                'required' => false,
                'sanitize' => 'sanitize_text_field',
                'validate' => function ($valor) {
                    return true;
                },
                'error' => ''
            ],
            'linkedin' => [
                'required' => false,
                'sanitize' => 'esc_url_raw',
                'validate' => function ($valor) {
                    return true;
                },
                'error' => ''
            ],

            'facebook' => [
                'required' => false,
                'sanitize' => 'esc_url_raw',
                'validate' => function ($valor) {
                    return true;
                },
                'error' => ''
            ],
            'twitter' => [
                'required' => false,
                'sanitize' => 'esc_url_raw',
                'validate' => function ($valor) {
                    return true;
                },
                'error' => ''
            ],
            'researchgate' => [
                'required' => false,
                'sanitize' => 'esc_url_raw',
                'validate' => function ($valor) {
                    return true;
                },
                'error' => ''
            ],

            'biografia' => [
                'required' => false,
                'sanitize' => 'wp_kses_post',
                'validate' => function ($valor) {
                    return true;
                },
                'error' => ''
            ],

        ];

        return $rules;
    }

    /*
     *  Function principal para importar el csv
     */
    public function process_csv()
    {
        $this->file_is_valid();
        $this->read_csv();
        $this->create_and_update_cpts();
        $this->inform_results();
    }

    /*
     *   Valida el formato del archivo y la cantidad de campos
     */
    protected function file_is_valid()
    {

        if ($this->csv_file['type'] !== 'text/csv') {
            wp_die('Error: el formato de archivo no es valido');
        }

        $handle = fopen($this->csv_file['tmp_name'], 'r');
        $cols = count(fgetcsv($handle));
        fclose($handle);

        if ($cols !== $this->num_columns_expected) {
            wp_die('Error: el archivo no contiene la cantidad de columnas esperadas');
        }
    }
    protected function map_csv_fields_to_cpt_fields()
    {
        return;
    }

    /*
     *   Lee el csv y decide por cada fila, si es para crear o actualizar un cpt
     */
    protected function read_csv()
    {
        $handle = fopen($this->csv_file['tmp_name'], "r");

        $headers = fgetcsv($handle);
        $headers = array_map('sanitize_title', $headers);

        $row_number = 2;

        while (($row = fgetcsv($handle)) !== FALSE) {

            // Skipeo filas vacias
            if (array_filter($row) === []) {
                $row_number++;
                continue;
            }

            // Valido la fila
            $row_result = $this->validate_row($row, $row_number, $headers);

            // Si hay errores, paso a la siguiente fila, sino almaceno qué hacer (creo nuevo cpt o actualizo)
            if ($row_result['error']) {
                $row_number++;
                continue;
            } else if ($row_result['create_new_cpt']) {
                array_push($this->cpts_to_create, $row);
            } else {
                array_push($this->cpts_to_update, $row);
            }

            $row_number++;
        }

        var_dump($this->errors);
        var_dump($this->cpts_to_create);
        var_dump($this->cpts_to_update);

        fclose($handle);
    }

    protected function create_and_update_cpts()
    {

    }

    protected function inform_results()
    {

    }

    /*
     *   Parsea los datos de la fila y los valida, si hay un error en alguno de los campos, guarda el error en $this->errors, sino retorna ...
     */
    protected function validate_row($row, $row_number, $headers)
    {
        $row_result = ['error' => false, 'create_new_cpt' => true];

        for ($i = 0; $i < $this->num_columns_expected; $i++) {

            $header = $headers[$i];
            $content = $row[$i];

            if (isset($this->rules[$header])) {
                // Sanitizo
                $this->rules[$header]['sanitize']($content);
                $is_valid = $this->rules[$header]['validate']($content);

                // Chequeo si el cpt existe (actualizo o lo creo)
                if ($header == 'email' && $is_valid) {
                    $ids = get_posts([
                        'post_type' => 'personal',
                        'meta_key' => 'email',
                        'meta_value' => $content,
                        'post_status' => 'any',
                        'numberposts' => 1,
                        'fields' => 'ids',
                    ]);

                    if (!empty($ids))
                        $row_result['create_new_cpt'] = false;
                }

                if (!$is_valid) {
                    $row_result['error'] = true;
                    $this->errors[] = [
                        'row' => $row_number,
                        'field' => $header,
                        'error' => $this->rules[$header]['error'],
                    ];
                    break;
                }

            } else
                var_dump("Error : la regla para el campo especificado no existe");

        }

        return $row_result;
    }


}

?>