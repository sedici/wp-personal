function procesar_formulario_personal(form) {

    alert('llego');

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

        beforeSend: function(){
            alert('Se esta por enviar');
        },
        
        success: function(response) {
            alert(response);
            console.log('Exito!');
        },

        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
        },
    });
};