<?php

namespace Personal\Inc\Admin;

class Csv_Importer
{

    protected $csv_file;

    protected int $num_columns_expected;
    protected array $cpts_to_create;
    protected array $cpts_to_update;

    protected array $personal_metadata;

    protected array $errors;

    protected $csv_config; // Parametros para pasarle a la funcion fopen y parsear el csv

    protected array $rules;

    public function __construct($csv_file)
    {
        $this->num_columns_expected = 16;
        $this->csv_file = $csv_file;
        $this->rules = $this->initialize_rules();

        $this->errors = [];
        $this->cpts_to_create = [];
        $this->cpts_to_update = [];
    }

    /**
     * Inicializa las reglas de validacion para cada columna del csv
     * @return array Array con las reglas de validacion
     */
    protected function initialize_rules()
    {
        $rules = [
            'email' => [
                'required' => true,
                'sanitize' => 'sanitize_email',
                'validate' => function ($value) {
                    return !empty($value) && is_email($value);
                },
                'error' => 'El email no debe ser vacio y el formato debe ser valido.'
            ],
            'nombre_apellido' => [
                'required' => true,
                'sanitize' => 'sanitize_text_field',
                'validate' => function ($value) {
                    return !empty($value);
                },
                'error' => 'El nombre no debe ser vacio'
            ],
            'telefono' => [
                'required' => false,
                'sanitize' => 'sanitize_text_field',
                'validate' => function ($valor) {
                    return preg_match('/^[\d\+\-\(\)\s]+$/', $valor);
                },
                'error' => 'El teléfono contiene caracteres no permitidos.'
            ],
            'unidad_de_investigacion' => [
                'required' => false,
                'sanitize' => 'sanitize_text_field',
                'validate' => function ($valor) {
                    return !preg_match('/^(http|www)/i', $valor);
                },
                'error' => 'La unidad de investigacion no puede ser un enlace'
            ],

            'rol_unidad_de_investigacion' => [
                'required' => false,
                'sanitize' => 'sanitize_text_field',
                'validate' => function ($valor) {
                    return !preg_match('/^(http|www)/i', $valor);
                },
                'error' => 'El rol de la unidad de investigacion no puede ser un enlace'
            ],
            'grado_alcanzado' => [
                'required' => false,
                'sanitize' => 'sanitize_text_field',
                'validate' => function ($valor) {
                    return !preg_match('/^(http|www)/i', $valor);
                },
                'error' => 'El grado alcanzado no puede ser un enlace'
            ],

            'google_scholar' => [
                'required' => false,
                'sanitize' => 'esc_url_raw',
                'validate' => function ($valor) {
                    return filter_var($valor, FILTER_VALIDATE_URL);
                },
                'error' => 'El enlace a google scholar no es valido'
            ],
            'orcid' => [
                'required' => false,
                'sanitize' => 'esc_url_raw',
                'validate' => function ($valor) {
                    return filter_var($valor, FILTER_VALIDATE_URL);
                },
                'error' => 'El enlace del orcid no es valido'
            ],
            'linkedin' => [
                'required' => false,
                'sanitize' => 'esc_url_raw',
                'validate' => function ($valor) {
                    return filter_var($valor, FILTER_VALIDATE_URL);
                },
                'error' => 'El enlace a linkedin no es valido'
            ],

            'facebook' => [
                'required' => false,
                'sanitize' => 'esc_url_raw',
                'validate' => function ($valor) {
                    return filter_var($valor, FILTER_VALIDATE_URL);
                },
                'error' => 'El enlace a facebook no es valido'
            ],
            'twitter' => [
                'required' => false,
                'sanitize' => 'esc_url_raw',
                'validate' => function ($valor) {
                    return filter_var($valor, FILTER_VALIDATE_URL);
                },
                'error' => 'El enlace a twitter no es valido'
            ],
            'researchgate' => [
                'required' => false,
                'sanitize' => 'esc_url_raw',
                'validate' => function ($valor) {
                    return filter_var($valor, FILTER_VALIDATE_URL);
                },
                'error' => 'El enlace a researchgate no es valido'
            ],

            'biografia' => [
                'required' => false,
                'sanitize' => 'wp_kses_post',
                'validate' => function ($valor) {
                    return true;
                },
                'error' => ''
            ],
            'sedici' => [
                'required' => false,
                'sanitize' => 'sanitize_text_field',
                'validate' => function ($valor) {
                    return true;
                },
                'error' => ''
            ],
            'cic' => [
                'required' => false,
                'sanitize' => 'sanitize_text_field',
                'validate' => function ($valor) {
                    return true;
                },
                'error' => ''
            ],
            'conicet' => [
                'required' => false,
                'sanitize' => 'sanitize_text_field',
                'validate' => function ($valor) {
                    return true;
                },
                'error' => ''
            ],
        ];

        return $rules;
    }

    /** 
     *  Function principal para importar el csv
     *  @return array Array con los resultados de la importacion
     */
    public function process_csv()
    {
        $this->file_is_valid();
        $this->validate_csv_headers();
        $this->validate_csv_content();
        $results = $this->create_and_update_cpts();
        return $this->inform_results($results);
    }

    /**
     * Valida el formato del archivo y la cantidad de campos
     * @throws \Exception Si el archivo no es de tipo 'text/csv' o si no coincide la cantidad de columnas.
     */
    protected function file_is_valid()
    {

        if ($this->csv_file['type'] !== 'text/csv') {
            throw new \Exception('Error: el formato de archivo no es valido');
        }

        $handle = fopen($this->csv_file['tmp_name'], 'r');
        $cols = count(fgetcsv($handle));
        fclose($handle);

        if ($cols !== $this->num_columns_expected) {
            throw new \Exception('Error: el archivo no contiene la cantidad de columnas esperadas');
        }
    }

    /**
     *   Mapea los campos del csv a los campos del cpt
     *   @param array $row Array con el contenido de una fila del csv
     *   @param array $headers Array con los nombres de las columnas del csv
     *   @return array Array con la estructura esperada por la función wp_insert_post
     * 
     */
    protected function map_csv_fields_to_cpt_fields($row, $headers)
    {
        $personal = array_combine($headers, $row);

        $args = [
            'post_title' => $personal['nombre_apellido'],
            'post_type' => 'personal',
            'post_status' => 'publish',
            'meta_input' => [
                'email' => $personal['email'],
                'telefono' => $personal['telefono'],
                'unidad_de_investigacion' => $personal['unidad_de_investigacion'],
                'rol_unidad_de_investigacion' => $personal['rol_unidad_de_investigacion'],
                'grado_alcanzado' => $personal['grado_alcanzado'],
                'biografia' => $personal['biografia'],
                'google_scholar' => $personal['google_scholar'],
                'orcid' => $personal['orcid'],
                'linkedin' => $personal['linkedin'],
                'facebook' => $personal['facebook'],
                'twitter' => $personal['twitter'],
                'researchgate' => $personal['researchgate'],
                'sedici' => $personal['sedici'],
                'cic' => $personal['cic'],
                'conicet' => $personal['conicet'],
            ]
        ];

        return $args;
    }

    /**
     *   Sanitiza los campos del personal antes de guardarlos
     *   @param array $personal 
     *   @return array
     * 
     */
    protected function sanitize_cpt_fields_before_save($personal)
    {
        $personal['post_title'] = $this->rules['nombre_apellido']['sanitize']($personal['post_title']);

        foreach ($personal['meta_input'] as $key => $value) {
            $personal['meta_input'][$key] = $this->rules[$key]['sanitize']($value);
        }

        return $personal;
    }

    /**
     *   Lee el csv, y por cada fila crea o actualiza un personal u omite la fila en caso de haber un error
     *   @return void
     * 
     */
    protected function validate_csv_content()
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

            } else {

                $personal = $this->map_csv_fields_to_cpt_fields($row, $headers);
                $personal = $this->sanitize_cpt_fields_before_save($personal);

                if ($row_result['create_new_cpt'])
                    array_push($this->cpts_to_create, $personal);
                else
                    array_push($this->cpts_to_update, $personal);
            }

            $row_number++;
        }

        fclose($handle);
    }

    /**
     * Crea y actualiza el personal en base al contenido de $this->cpts_to_create y $this->cpts_to_update
     * 
     * @return array{personal_created: int, personal_updated: int}
     */
    protected function create_and_update_cpts()
    {
        $count_created = 0;
        $count_updated = 0;

        // Si hay cpts_to_create, los creo

        if (!empty($this->cpts_to_create)) {
            foreach ($this->cpts_to_create as $personal) {
                $result = wp_insert_post($personal);
                if ($result !== 0)
                    $count_created++;
            }
        }

        // Si hay cpts_to_update, los actualizo
        if (!empty($this->cpts_to_update)) {
            foreach ($this->cpts_to_update as $personal) {
                $id = $this->get_cpt_id($personal['meta_input']['email']);
                if (!empty($id)) {
                    $personal['ID'] = $id;
                    $result = wp_update_post($personal);
                    if ($result !== 0)
                        $count_updated++;
                } else {
                    $this->errors[] = 'Error al actualizar: no se encontro el personal con el email : ' . $personal['meta_input']['email'];
                }
            }
        }

        return [
            'personal_created' => $count_created,
            'personal_updated' => $count_updated,
        ];
    }

    /**
     * Obtiene el id de un cpt personal por email
     * 
     * @param string $email Email del personal
     * @return int
     */
    protected function get_cpt_id($email)
    {
        $id = get_posts([
            'post_type' => 'personal',
            'meta_key' => 'email',
            'meta_value' => $email,
            'post_status' => 'any',
            'numberposts' => 1,
            'fields' => 'ids',
        ]);

        return $id[0];
    }

    /**
     * Informa los resultados de la importacion
     * 
     * @param array $results Array con el numero de perfiles creados y actualizados 
     * @return array
     */
    protected function inform_results($results)
    {
        return [
            'personal_created' => 'Se crearon : ' . $results['personal_created'] . ' perfiles de personal',
            'personal_updated' => 'Se actualizaron : ' . $results['personal_updated'] . ' perfiles de personal',
            'errors' => $this->errors,
        ];
    }

    /**
     * Valida los headers del csv
     * 
     * @throws \Exception Si habia una columna en el csv que no se correspondia con una regla en $this->rules
     * @return void
     */
    protected function validate_csv_headers()
    {
        try {
            $handle = fopen($this->csv_file['tmp_name'], "r");

            $headers = fgetcsv($handle);
            $headers = array_map('sanitize_title', $headers);

            foreach ($headers as $header) {
                if (!isset($this->rules[$header])) {
                    $aux = ($header === '') ? "VACIO" : $header;
                    throw new \Exception("Error : " . $aux . " no es un campo valido para procesar");
                }
            }
        } finally {
            fclose($handle);
        }
    }

    /**
     *   Parsea los datos de la fila recibida y los valida, 
     *   si hay un error en alguno de los campos, guarda el error en $this->errors, sino retorna un array
     *   con el resultado de la validacion
     * 
     * @param array $row Array con los datos de la fila
     * @param int $row_number Numero de la fila
     * @param array $headers Array con los headers de la fila
     * @return array Array con el resultado de la validacion (error, create_new_cpt)
     */
    protected function validate_row($row, $row_number, $headers)
    {
        $row_result = ['error' => false, 'create_new_cpt' => true];

        for ($i = 0; $i < $this->num_columns_expected; $i++) {

            $header = $headers[$i];
            $content = $row[$i];

            // Sanitizo
            $content_sanitized = $this->rules[$header]['sanitize']($content);
            $is_valid = $this->rules[$header]['validate']($content_sanitized);

            // Chequeo si el cpt existe (actualizo o lo creo)
            if ($header == 'email' && $is_valid) {
                $ids = get_posts([
                    'post_type' => 'personal',
                    'meta_key' => 'email',
                    'meta_value' => $content_sanitized,
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


        }

        return $row_result;
    }


}

?>