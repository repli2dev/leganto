/**
 * AJAX Nette Framwork plugin for jQuery
 *
 * @copyright   Copyright (c) 2009 Jan Marek
 * @license     MIT
 * @link        http://nettephp.com/cs/extras/jquery-ajax
 * @version     0.2
 */

jQuery.extend({
	nette: {
		updateSnippet: function (id, html) {
			$("#" + id).html(html);
		},

		success: function (payload) {
			// redirect
			if (payload.redirect) {
				window.location.href = payload.redirect;
				return;
			}

			// snippets
			if (payload.snippets) {
				for (var i in payload.snippets) {
					jQuery.nette.updateSnippet(i, payload.snippets[i]);
				}
			}
		}
	}
});

jQuery.ajaxSetup({
	success: jQuery.nette.success,
	dataType: "json"
});

$(function() {
        // nastaví událost onclick pro všechny elementy A s třídou 'ajax'
        $("a.ajax").live("click", function(event) {
                $.get(this.href); // zahájí AJAXový požadavek

                // zobrazí spinner, signalizující uživateli, že se něco děje
                $('<div id="ajax-spinner"></div>').css({
                        position: "absolute",
                        left: event.pageX + 20,
                        top: event.pageY + 40

                }).ajaxStop(function() {
                        $(this).remove(); // po skončení spinner smaž

                }).appendTo("body");

                return false;
        });
}); 