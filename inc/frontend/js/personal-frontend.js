(function( $ ) {
	'use strict';

	// Acordeón de publicaciones de la vista single-personal: los paneles
	// arrancan cerrados (ver single-personal.css) y el header alterna la
	// clase is-open del item.
	$(function () {
		$(document).on('click', '.personal-accordion-header', function () {
			$(this).closest('.personal-accordion-item').toggleClass('is-open');
		});
	});

})( jQuery );
