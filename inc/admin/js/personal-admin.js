function procesar_formulario_personal(form) {

    var formData = jQuery(form).serialize();

    // Realizar la solicitud AJAX
    jQuery.ajax({
        type: "POST",
        url: personal_ajax_object.url,
        data: {
            action: "generate_shortcode_personal",
            dataType: "json",
            formulario_data: formData
        },

        success: function (response) {

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

        error: function (jqXHR, textStatus, errorThrown) {
            console.error('Error en la solicitud AJAX de personal:', textStatus, errorThrown);
        },
    });
};

function process_csv_form(form) {

    let formData = new FormData(form);
    let $results = jQuery('#csv-import-results');

    // Preparar el contenedor
    $results.show().html('<p>Procesando...</p>');

    jQuery.ajax({
        type: "POST",
        url: personal_ajax_object.url,
        data: formData,
        processData: false,
        contentType: false,

        success: function (response) {
            let html = '';
            
            if (response.success) {
                // Si todo fue bien (aunque haya errores en filas individuales)
                html += '<div class="notice notice-success"><p>' + response.data.personal_created + '</p></div>';
                html += '<div class="notice notice-success"><p>' + response.data.personal_updated + '</p></div>';

                if (response.data.errors && response.data.errors.length > 0) {
                    html += '<div class="notice notice-warning"><h4>Errores en filas:</h4><ul>';
                    response.data.errors.forEach(function(e) {
                        html += '<li>Fila ' + e.row + ' (' + e.field + '): ' + e.error + '</li>';
                    });
                    html += '</ul></div>';
                }
            } else {
                // Error de seguridad o archivo no válido
                html = '<div class="notice notice-error"><p>' + response.data + '</p></div>';
            }
            
            $results.html(html);
        },

        error: function (jqXHR, textStatus, errorThrown) {
            $results.html('<div class="notice notice-error"><p>Error de conexión con el servidor.</p></div>');
        },
    });
}

function refrescar_categorias() {

    // Realizar la solicitud AJAX
    jQuery.ajax({
        type: "POST",
        url: personal_ajax_object.url,
        data: {
            action: "generate_shortcode_personal"
        },

        success: function (response) {
            console.log("Categorias actualizadas!" + response);
        },

        error: function (jqXHR, textStatus, errorThrown) {
            console.error('Error en la actualizacion de categorias de personal:', textStatus, errorThrown);
        },
    });
};