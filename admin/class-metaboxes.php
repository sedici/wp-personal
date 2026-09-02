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

    private const NONCE_ACTION = 'personal_nonce_update_cpt';
    private const NONCE_FIELD_NAME = 'meta_box_nonce';

    /**
     * Constructor de la clase.
     */
    public function __construct() {}

    /**
     * Guarda y actualiza los campos personalizados en la base de datos (Post Meta)
     * cuando se publica o actualiza un post de tipo 'personal'.
     * @param int $idpersonal ID del post que se está guardando.
     */
        public function save($idpersonal) {
        
        $personal = get_post($idpersonal);

        if (!$personal || $personal->post_type !== 'personal') {
            return;
        }

        // if our nonce isn't there, or we can't verify it, bail.
		if ( ! isset( $_POST[self::NONCE_FIELD_NAME] ) || ! wp_verify_nonce( $_POST[self::NONCE_FIELD_NAME] , self::NONCE_ACTION ) ) {
            error_log("nonce invalido");
            return;
		}

        // HERA solo puede habilitarse si el ORCID está completo.
        if (empty($_POST['orcid'])) {
            unset($_POST['hera_enabled']);
        }

        foreach ($this->getInputsPersonal() as $field) {
            switch ($field['type']) {
                case 'checkbox':
                    $resultado = update_post_meta($idpersonal, $field['name'], isset($_POST[$field['name']]) ? '1' : '');
                    break;

                case 'file':
                    $resultado = $this->saveFileField($idpersonal, $field['name']);
                    break;

                default:
                    if (isset($_POST[$field['name']])) {
                        $value = call_user_func($field['sanitize_callback'], $_POST[$field['name']]);
                        $resultado = update_post_meta($idpersonal, $field['name'], $value);
                    }
            }
        }
    }

    /**
     * Procesa la subida del Curriculum Vitae (PDF) para el campo indicado.
     */
    private function saveFileField($idpersonal, $field_name) {
        if ($field_name !== 'curriculum_vitae' || empty($_FILES['curriculum_vitae']['name'])) {
            return;
        }

        if ($_FILES['curriculum_vitae']['error'] !== UPLOAD_ERR_OK) {
            wp_die('Error al subir el archivo. Código de error: ' . $_FILES['curriculum_vitae']['error']);
        }

        $arr_file_type = wp_check_filetype(basename($_FILES['curriculum_vitae']['name']));

        if ($arr_file_type['type'] !== 'application/pdf') {
            wp_die('El tipo de archivo que subiste no es un PDF.');
        }

        $upload = wp_upload_bits(
            $_FILES['curriculum_vitae']['name'],
            null,
            file_get_contents($_FILES['curriculum_vitae']['tmp_name'])
        );

        if (isset($upload['error']) && $upload['error'] != 0) {
            wp_die('Ocurrió un error subiendo el archivo. El error es: ' . $upload['error']);
        }

        update_post_meta($idpersonal, $field_name, $upload);
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
     * Inicializa y define el esquema completo de los campos del formulario de Personal
     * Setea en $inputs_personal la lista de campos del CPT personal a partir de metabox-config
     */
    private function initializeInputsPersonal()
    {
        // Normaliza los campos del JSON y construye la lista de inputs
        $inputs = array_map(
            [$this, 'normalizeField'],
            $this->loadJsonFields()
        );

        // Obtiene los nombres de los campos existentes
        $existing_names = array_column($inputs, 'name');

        // Incluye los campos de repositorios
        $inputs = array_merge($inputs, $this->buildRepositoryFields($existing_names));

        $this->inputs_personal = $inputs;
    }

    /**
     * Lee y decodifica metaboxes-config.json.
     */
    private function loadJsonFields(): array {
        $json_file = __DIR__ . '/metaboxes-config.json';

        if (!file_exists($json_file)) {
            return [];
        }

        return json_decode(file_get_contents($json_file), true) ?: [];
    }

    /**
     * Normaliza un campo del JSON a la estructura esperada save() y las vistas.
     */
    private function normalizeField(array $field): array {
        $field['class']             = $field['class'] ?? '';
        $field['placeholder']       = $field['placeholder'] ?? '';
        $field['default_value']     = $field['default_value'] ?? '';
        $field['sanitize_callback'] = $field['validation']['sanitize_callback'] ?? 'sanitize_text_field';

        if ($field['name'] === 'unidad_de_investigacion' && empty($field['default_value'])) {
            $field['default_value'] = get_bloginfo('name');
        }

        if (!empty($field['icon'])) {
            $field['label'] = $this->buildIconLabel($field['icon'], $field['label']);
        }

        return $field;
    }

    /**
     * Construye los campos de repositorios con la misma estructura que los demas campos
     */
    private function buildRepositoryFields(array $existing_names): array
    {
        $repositories = apply_filters('wp_dspace_registered_repositories', []);
        $fields = [];

        foreach ($repositories as $key => $repository) {
            $field_name = self::REPO_FIELD_NAMES[$key] ?? $key;

            if (in_array($field_name, $existing_names, true)) {
                continue;
            }

            $fields[] = [
                'name'              => $field_name,
                'type'              => 'text',
                'label'             => $this->buildIconLabel($key . '.png', ucwords(str_replace('-', ' ', $key))),
                'icon'              => $key . '.png',
                'instructions'      => 'Debe completar con el nombre EXACTO del perfil dentro del repositorio, por ejemplo: Villareal,Gonzalo Luján',
                'placeholder'       => '',
                'default_value'     => '',
                'class'             => '',
                'sanitize_callback' => 'sanitize_text_field',
            ];
        }

        return $fields;
    }

    /**
     * Genera el HTML de una etiqueta con ícono.
     */
    private function buildIconLabel(string $icon, string $label): string
    {
        return '<img src="' . \Personal\PLUGIN_NAME_URL . 'assets/images/' . $icon . '" height="32"> ' . $label;
    }
}
