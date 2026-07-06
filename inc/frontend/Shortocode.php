  <?php 
   
   
   public function generate_shortcode_personal()
    {

        $shortcode = "";

        $form_data = $_POST['formulario_data'];

        // Guardo los input del formulario en un array
        $form_data_array = explode("&", $form_data);


        //Usando expresiones regulares obtengo los valores numericos de los campos del formulario
        $diccionario_shortcode = array(
            "term_id_selected" => preg_replace('/[^0-9]/', '', $form_data_array[0]),
            "columns" => preg_replace('/[^0-9]/', '', $form_data_array[1]),
        );

        //Verifica que term_id y columns sea valido

        if (
            !empty($diccionario_shortcode['term_id_selected']) &&
            !empty($diccionario_shortcode['columns']) &&
            ($diccionario_shortcode['columns'] >= 1 && $diccionario_shortcode['columns'] <= 4)
        ) {

            $shortcode = "[list-personal category_id=" . $diccionario_shortcode['term_id_selected'] . " columns=" . $diccionario_shortcode['columns'] . "]";

            echo $shortcode;

        } else {
            echo 'Ocurrio un error';
        }

        wp_die();
    }



        public function show_view()
    {

        include_once dirname(__DIR__) . '/admin/views/personal-shortcode-generator-view.php';

    }