<?php
  namespace Personal\Inc\Admin;

  class Metaboxes
    {

        private $inputs_personal;
        private $repositories;

        public function __construct(){}







        public function set_repositories(array $repos): void
        {
        error_log('set_repositories fue llamado con: ' . print_r($repos, true));

        $this->repositories = $repos;
        $this->initializeInputsPersonal();
        }


    /**
     * Formulario custom post
     */
    public function render($post)
    {
        include_once('views/personal-view.php');
    }
    /**
     * Guarda los campos personalizados del post
     */
    public function save($idpersonal)
    {
        $personal = get_post($idpersonal);

        if ($personal->post_type == 'personal') {
            $inputs = $this->getInputsPersonal();
            foreach ($inputs as $input) {

                if (isset($input['name']) and isset($_POST[$input['name']]))
                    update_post_meta($idpersonal, $input['name'], $_POST[$input['name']]);
                if (isset($input['repositories'])) {
                    foreach ($input['repositories'] as $repository) {
                        if (isset($_POST[$repository['name']]))
                            update_post_meta($idpersonal, $repository['name'], $_POST[$repository['name']]);
                    }
                }
            }
            if (!empty($_FILES['curriculum_vitae']['name'])) {
                $supported_types = array('application/pdf');
                $arr_file_type = wp_check_filetype(basename($_FILES['curriculum_vitae']['name']));
                $uploaded_type = $arr_file_type['type'];

                if (in_array($uploaded_type, $supported_types)) {
                    $upload = wp_upload_bits($_FILES['curriculum_vitae']['name'], null, file_get_contents($_FILES['curriculum_vitae']['tmp_name']));
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


    public function getInputsPersonal()
    {
        if (empty($this->inputs_personal)) {
            $this->initializeInputsPersonal();
        }
        return $this->inputs_personal;
    }


    public function personal_display_callback($post)
    {
        include_once('views/personal-view.php');
    }

    /**
     * Agrega los campos personalizados para el custom post.
     */
    public function register()
    {
        add_meta_box('personal_meta', __('Información del personal', 'personal'), array($this, 'personal_display_callback'), 'personal');
    }


  private function initializeInputsPersonal()
    {

        $repositories = apply_filters('wp_dspace_registered_repositories', []);
        error_log(print_r($repositories, true));

        $repository_inputs= [];
        foreach ($repositories as $key => $repository) {
            $repository_inputs[] = array(
                'class' => '',
                'label'=> '<img src="' . plugins_url() . '/personal/assets/images/' . $key . '.png" height="32"> ' . ucwords(str_replace('-', ' ', $key)),
                'name' => $key,
                'type' => 'text',
                'instructions' => 'Debe completar con el nombre EXACTO del perfil dentro del repostirio, por ejemplo: Villareal,Gonzalo Luján',
                'default_value' => '',
                'placeholder' => '',
            );
        }

        error_log(print_r($repository_inputs, true));


        $this->inputs_personal = array(
            array(
                'class' => '',
                'label' => '<img src="' . plugins_url() . '/personal/assets/images/email.png" height="32"> Email ',
                'name' => 'email',
                'type' => 'email',
                'instructions' => 'Correo electrónico',
                'default_value' => '',
                'placeholder' => 'Email',
            ),
            array(
                'class' => '',
                'label' => '<img src="' . plugins_url() . '/personal/assets/images/tel.png" height="32">	Teléfono',
                'name' => 'telefono',
                'type' => 'text',
                'instructions' => 'Teléfono',
                'default_value' => '',
                'placeholder' => '0221-11111111',
            ),
            array(
                'key' => 'field_59dd232d52523',
                'label' => 'Unidad de investigación',
                'name' => 'unidad_de_investigacion',
                'type' => 'text',
                'instructions' => 'Unidad de investigación a la que pertenece',
                'default_value' => get_bloginfo('name'),  // Es el nombre del sitio 
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_59dd235252524',
                'label' => '<img src="' . plugins_url() . '/personal/assets/images/grado_alcanzado.png" height="32">	 Grado Alcanzado',
                'name' => 'grado_alcanzado',
                'type' => 'text',
                'instructions' => 'Grado alcanzado',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_59dd238952525',
                'label' => '<img src="' . plugins_url() . '/personal/assets/images/google_scholar.png" width="32" height="32"> Google Scholar ',
                'name' => 'google_scholar',
                'type' => 'url',
                'instructions' => 'http://scholar.google.com/citations?user=xxxxxx',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_59dd241852526',
                'label' => '<img src="' . plugins_url() . '/personal/assets/images/orcid.gif" width="32" height="32"> ORCID',
                'name' => 'orcid',
                'type' => 'url',
                'instructions' => 'https://orcid.org/xxxx-xxxx-xxxx-xxxx',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_59dd244f52527',
                'label' => '<img src="' . plugins_url() . '/personal/assets/images/research-gate.png" width="32" height="32"> ResearchGate',
                'name' => 'researchgate',
                'type' => 'url',
                'instructions' => 'https://www.researchgate.net/profile/xxxxxxx',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_59dd244f534434',
                'label' => '<img src="' . plugins_url() . '/personal/assets/images/linkedin.png" width="32" height="32"> Linkedin',
                'name' => 'linkedin',
                'type' => 'url',
                'instructions' => 'https://www.linkedin.com/in/xxxxxxx',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_59dd244f5343',
                'label' => '<img src="' . plugins_url() . '/personal/assets/images/facebook.jpg" width="32" height="32"> Facebook',
                'name' => 'facebook',
                'type' => 'url',
                'instructions' => 'https://www.facebook.com/xxxxxxx',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_59dd244f5344',
                'label' => '<img src="' . plugins_url() . '/personal/assets/images/instagram.png" width="32" height="32"> Instagram',
                'name' => 'instagram',
                'type' => 'url',
                'instructions' => 'https://www.instagram.com/xxxxxxx',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_59dd244f5434334',
                'label' => '<img src="' . plugins_url() . '/personal/assets/images/twitter.png" width="32" height="32"> Twitter',
                'name' => 'X',
                'type' => 'url',
                'instructions' => 'https://twitter.com/xxxxxxx',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_59dd25596cb02',
                'label' => '<img src="' . plugins_url() . '/personal/assets/images/biography.png" height="32">	Biografía',
                'name' => 'biografia',
                'type' => 'textarea',
                /*'size'=>'15',
                'maxlength' =>'30',*/
                'default_value' => '',
                'media_upload' => 'no',
            ),
            array(
                'key' => 'field_59dd25736cb03',
                'label' => '<img src="' . plugins_url() . '/personal/assets/images/cv.png" height="32">	Curriculum Vitae',
                'name' => 'curriculum_vitae',
                'type' => 'file',
                'save_format' => 'object',
                'library' => 'all',
            ),
            array(
                'repositories' => $repository_inputs,
            ),   
        );
        
    }



    }