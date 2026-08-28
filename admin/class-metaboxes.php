<?php
namespace Personal\Admin;

/**
 * Clase Metaboxes
 * 
 * Gestiona la configuración, renderizado y guardado de los campos personalizados (metaboxes)
 * para el Custom Post Type 'personal'.
 */
class Metaboxes
{
    /**
     * Almacena la configuración de los campos del formulario de personal.
     * @var array
     */
    private $inputs_personal;

    /**
     * Mapa slug del repositorio en wp-dspace-v2 => nombre del campo meta.
     * Los campos meta conservan los nombres históricos ('cic', etc.) para
     * que los posts existentes y el import/export CSV sigan funcionando.
     */
    private const REPO_FIELD_NAMES = [
        'cic-digital' => 'cic',
    ];

    /**
     * Constructor de la clase.
     */
    public function __construct() {}

    /**
     * Guarda y actualiza los campos personalizados en la base de datos (Post Meta)
     * cuando se publica o actualiza un post de tipo 'personal'.
     * @param int $idpersonal ID del post que se está guardando.
     */
    public function save($idpersonal)
    {
        $personal = get_post($idpersonal);

        if ($personal->post_type == 'personal') {
            // HERA solo puede habilitarse si el ORCID está completo.
            if (empty($_POST['orcid'])) {
                unset($_POST['hera_enabled']);
            }
            
            $inputs = $this->getInputsPersonal();
            foreach ($inputs as $input) {

                // Los checkbox no llegan en $_POST cuando están desmarcados,
                // por eso se guardan de forma explícita. Solo si el formulario
                // del metabox fue enviado (evita borrarlos en quick edit/autosave).
                if (isset($input['type']) && $input['type'] == 'checkbox') {
                    if (isset($_POST['meta_box_nonce'])) {
                        update_post_meta($idpersonal, $input['name'], isset($_POST[$input['name']]) ? '1' : '');
                    }
                    continue;
                }
                
                // Guarda campos estándar
                if (isset($input['name']) && isset($_POST[$input['name']])) {
                    update_post_meta($idpersonal, $input['name'], $_POST[$input['name']]);
                }
                
                // Guarda campos de repositorios dinámicos si existen
                if (isset($input['repositories'])) {
                    foreach ($input['repositories'] as $repository) {
                        if (isset($_POST[$repository['name']])) {
                            update_post_meta($idpersonal, $repository['name'], $_POST[$repository['name']]);
                        }
                    }
                }
            }

            // Procesamiento de la subida del Curriculum Vitae (PDF)
            if (!empty($_FILES['curriculum_vitae']['name'])) {
                $supported_types = array('application/pdf');
                $arr_file_type = wp_check_filetype(basename($_FILES['curriculum_vitae']['name']));
                $uploaded_type = $arr_file_type['type'];

                if (in_array($uploaded_type, $supported_types)) {
                    $upload = wp_upload_bits(
                        $_FILES['curriculum_vitae']['name'], 
                        null, 
                        file_get_contents($_FILES['curriculum_vitae']['tmp_name'])
                    );
                    
                    if (isset($upload['error']) && $upload['error'] != 0) {
                        wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
                    } else {
                        update_post_meta($idpersonal, 'curriculum_vitae', $upload);
                    }
                } else {
                    wp_die("The file type that you've uploaded is not a PDF.");
                }
            }
        }
    }

    /**
     * Retorna la configuración estructurada de todos los campos personalizados.
     * 
     * Si la propiedad privada `$inputs_personal` no ha sido inicializada,
     * invoca el método correspondiente para estructurarla.
     * 
     * @return array Configuración de los campos.
     */
    public function getInputsPersonal()
    {
        if (empty($this->inputs_personal)) {
            $this->initializeInputsPersonal();
        }
        return $this->inputs_personal;
    }

    /**
     * Callback invocado por WordPress para dibujar el contenido interno del metabox.
     * Incluye el archivo de vista que renderiza los campos del formulario.
     * 
     * @param WP_Post $post Objeto del post actual de WordPress.
     */
    public function personal_display_callback($post)
    {
        include_once('views/personal-view.php');
    }

    /**
     * Registra el panel meta (Metabox) "Información del personal" dentro de la pantalla
     * de edición del Custom Post Type 'personal' en el panel de administración.
     */
    public function register()
    {
        add_meta_box(
            'personal_meta', 
            __('Información del personal', 'personal'), 
            array($this, 'personal_display_callback'), 
            'personal'
        );
    }

    /**
     * Inicializa y define el esquema completo de los campos del formulario de Personal.
     * 
     * Construye una lista detallada con tipos de inputs, etiquetas, instrucciones, placeholders
     * e iconos descriptivos a partir del archivo JSON metaboxes-config.json. Además, incorpora de
     * forma dinámica los repositorios registrados (por ejemplo, dspace) mediante filtros de WordPress.
     */
    private function initializeInputsPersonal()
    {
        $json_file = __DIR__ . '/metaboxes-config.json';
        $json_fields = [];
        if (file_exists($json_file)) {
            $json_data = file_get_contents($json_file);
            $json_fields = json_decode($json_data, true) ?: [];
        }

        $inputs = [];
        $existing_names = [];

        foreach ($json_fields as $field) {
            // Asegurar campos mínimos requeridos por las vistas y prevenir PHP notices
            $field['class']         = $field['class'] ?? '';
            $field['placeholder']   = $field['placeholder'] ?? '';
            $field['default_value'] = $field['default_value'] ?? '';

            // Dinámicamente asignar el nombre del sitio para la unidad de investigación
            if ($field['name'] === 'unidad_de_investigacion' && empty($field['default_value'])) {
                $field['default_value'] = get_bloginfo('name');
            }

            // Construir la etiqueta con imagen si tiene icono
            if (!empty($field['icon'])) {
                $field['label'] = '<img src="' . \Personal\PLUGIN_NAME_URL . 'assets/images/' . $field['icon'] . '" height="32"> ' . $field['label'];
            }

            $inputs[] = $field;
            $existing_names[] = $field['name'];
        }

        // Obtiene los repositorios adicionales registrados dinámicamente por el plugin wp-dspace-v2
        $repositories = apply_filters('wp_dspace_registered_repositories', []);
        $repository_inputs = [];

        foreach ($repositories as $key => $repository) {
            $field_name = self::REPO_FIELD_NAMES[$key] ?? $key;

            // Evitar duplicar repositorios si ya están definidos estáticamente en el JSON
            if (in_array($field_name, $existing_names)) {
                continue;
            }

            $repository_inputs[] = array(
                'class'         => '',
                'label'         => '<img src="' . \Personal\PLUGIN_NAME_URL . 'assets/images/' . $key . '.png" height="32"> ' . ucwords(str_replace('-', ' ', $key)),
                'name'          => $field_name,
                'type'          => 'text',
                'instructions'  => 'Debe completar con el nombre EXACTO del perfil dentro del repostirio, por ejemplo: Villareal,Gonzalo Luján',
                'default_value' => '',
                'placeholder'   => '',
            );
        }

        // Si existen repositorios dinámicos adicionales, los agregamos bajo la estructura agrupada esperada por las vistas
        if (!empty($repository_inputs)) {
            $inputs[] = array(
                'repositories' => $repository_inputs,
            );
        }

        $this->inputs_personal = $inputs;
    }

    /**
     * Retorna la configuración de un metabox específico por su nombre (name).
     * 
     * @param string $name Nombre del metabox a buscar.
     * @return array Datos del metabox o un array vacío si no se encuentra.
     */
    public function get_field(string $name): array
    {
        $inputs = $this->getInputsPersonal();
        foreach ($inputs as $input) {
            if (isset($input['name']) && $input['name'] === $name) {
                return $input;
            }
            // También busca dentro de los repositorios dinámicos agrupados si los hubiera
            if (isset($input['repositories'])) {
                foreach ($input['repositories'] as $repository) {
                    if (isset($repository['name']) && $repository['name'] === $name) {
                        return $repository;
                    }
                }
            }
        }
        return [];
    }
}
