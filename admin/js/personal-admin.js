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

// ==========================================================================
//   INTERFAZ DE USUARIO: EFECTOS Y VALIDACIONES DEL METABOX
// ==========================================================================
document.addEventListener('DOMContentLoaded', function() {


    const postForm = document.getElementById('post');
    if (postForm) {
        // Inyectamos novalidate dinámicamente para que el navegador nos deje el control a nosotros
        postForm.setAttribute('novalidate', 'novalidate');
    }
    
    // --- 1. Efectos Visuales (Foco) ---
    const inputs = document.querySelectorAll('.personal-input-col input');

    inputs.forEach(input => {
        // Cuando el usuario entra al campo
        input.addEventListener('focus', function() {
            const row = this.closest('.personal-field-row');
            if(row) {
                row.style.backgroundColor = '#f0f6fc'; // Un azul muy claro
                row.style.borderLeft = '4px solid #2271b1';
                row.style.paddingLeft = '16px'; // Compensar el borde
            }
        });

        // Cuando el usuario sale del campo
        input.addEventListener('blur', function() {
            const row = this.closest('.personal-field-row');
            if(row) {
                row.style.backgroundColor = '';
                row.style.borderLeft = 'none';
                row.style.paddingLeft = '0'; // Restaurar padding
            }
        });
    });

    // --- 2. Funciones Auxiliares de Error ---
    function showError(input, message) {
        clearError(input); // Evita duplicados
        
        input.style.borderColor = '#d63638';
        input.style.boxShadow = '0 0 0 1px #d63638';
        input.classList.add('js-personal-invalid'); // Marca interna para buscar errores
        
        const errorSpan = document.createElement('span');
        errorSpan.className = 'personal-js-error';
        errorSpan.style.color = '#d63638';
        errorSpan.style.fontSize = '12px';
        errorSpan.style.display = 'block';
        errorSpan.style.marginTop = '5px';
        errorSpan.style.fontWeight = '500';
        errorSpan.innerText = message;
        
        input.parentNode.appendChild(errorSpan);
    }

    function clearError(input) {
        input.style.borderColor = '';
        input.style.boxShadow = '';
        input.classList.remove('js-personal-invalid');
        
        const existingError = input.parentNode.querySelector('.personal-js-error');
        if (existingError) {
            existingError.remove();
        }
    }

    // --- 3. Lógica de Validación ---
    function validarEmail(input) {
        const value = input.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (value !== '' && !emailRegex.test(value)) {
            showError(input, 'Por favor, ingresa una dirección de correo electrónico válida.');
            return false;
        }
        clearError(input);
        return true;
    }

    function validarUrl(input) {
        const value = input.value.trim();
        const urlRegex = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/i;
        
        if (value !== '' && !urlRegex.test(value)) {
            showError(input, 'Por favor, ingresa una URL válida (ej: https://ejemplo.com).');
            return false;
        }
        clearError(input);
        return true;
    }

    // --- 4. Asignar Eventos a los Inputs ---
    const emailInputs = document.querySelectorAll('.personal-input-col input[type="email"]');
    const urlInputs = document.querySelectorAll('.personal-input-col input[type="url"]');
    const allValidatedInputs = document.querySelectorAll('.personal-input-col input[type="email"], .personal-input-col input[type="url"]');

    // Eventos al salir del campo (blur) y al escribir (input)
    emailInputs.forEach(input => {
        input.addEventListener('blur', () => validarEmail(input));
        input.addEventListener('input', () => clearError(input));
    });

    urlInputs.forEach(input => {
        input.addEventListener('blur', () => validarUrl(input));
        input.addEventListener('input', () => clearError(input));
    });

    // EVITAR EL ENTER: Frenar el envío y validar el campo actual
    allValidatedInputs.forEach(input => {
        input.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // Frena la acción nativa del navegador
                
                // Forzamos la validación para mostrar el error si está mal
                if (this.type === 'email') validarEmail(this);
                if (this.type === 'url') validarUrl(this);
            }
        });
    });

    // --- 5. HERA: el checkbox solo se puede habilitar con un ORCID cargado ---
    const heraCheckbox = document.getElementById('hera_enabled');
    const orcidInput = document.getElementById('orcid');

    if (heraCheckbox && orcidInput) {
        function toggleHeraCheckbox() {
            const hasOrcid = orcidInput.value.trim() !== '';
            heraCheckbox.disabled = !hasOrcid;
            if (!hasOrcid) {
                heraCheckbox.checked = false;
            }
        }

        toggleHeraCheckbox();
        orcidInput.addEventListener('input', toggleHeraCheckbox);
    }

    // --- 6. Bloquear el botón de "Actualizar/Publicar" si hay errores ---
    
    if (postForm) {
        postForm.addEventListener('submit', function(event) {
            let hasErrors = false;

            // Revisamos todo antes de dejar pasar el guardado
            emailInputs.forEach(input => {
                if (!validarEmail(input)) hasErrors = true;
            });

            urlInputs.forEach(input => {
                if (!validarUrl(input)) hasErrors = true;
            });

            if (hasErrors) {
                event.preventDefault(); // Frena el guardado de WordPress
                
                // Hace scroll automático hacia el primer error y lo selecciona
                const firstError = document.querySelector('.js-personal-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }

                // Truco para Gutenberg: destrabar el botón de guardado que queda en "Guardando..."
                setTimeout(() => {
                    if (wp && wp.data && wp.data.dispatch) {
                        wp.data.dispatch('core/editor').enablePublishSidebar();
                    }
                }, 500);
            }
        });
    }
})