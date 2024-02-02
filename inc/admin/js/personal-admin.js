function procesar_formulario_personal(form) {

    var formData = jQuery(form).serialize();

    // Realizar la solicitud AJAX
    jQuery.ajax({
        type: "POST",
        url: personal_ajax_object.url, 
        data: {
            action: "generate_shortcode_personal",
            dataType : "json",
            formulario_data: formData
        },
        
        success: function(response) {

            if (response !== 1) {

                document.getElementById("shortcode-resultante").innerHTML = 
                "<div class='wrapper-resultado-shortcode-personal'> <p class='texto-resultado-shortcode-personal'> " + response + " </p> </div>";

                console.log('Se genero el shortcode con extio');
            }
            else {

                document.getElementById("shortcode-resultante").innerHTML = 
                "<div class='wrapper-resultado-shortcode-personal'> <p class='texto-resultado-shortcode-personal'> Ocurrio un error! </p> </div>";
                console.log(response);
            }

        },

        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error en la solicitud AJAX de personal:', textStatus, errorThrown);
        },
    });
};


function refrescar_categorias() {

    // Realizar la solicitud AJAX
    jQuery.ajax({
        type: "POST",
        url: personal_ajax_object.url, 
        data: {
            action: "generate_shortcode_personal"
        },
        
        success: function(response) {
            console.log("Categorias actualizadas!" + response);
        },

        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error en la actualizacion de categorias de personal:', textStatus, errorThrown);
        },
    });
};