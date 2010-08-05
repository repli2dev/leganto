$(function() {
	var cache = {};
	// Small form in header
	$( "#search #frmform-query" ).autocomplete({
		minLength: 2,
		source: function(request, response) {
			// Turn off when not searching books
			if($("#frmform-search").val() != "default") return;
			if ( request.term in cache ) {
				response( cache[ request.term ] );
				return;
			}

			$.ajax({
				url: "/book/suggest/",
				dataType: "json",
				data: request,
				success: function( data ) {
					cache[ request.term ] = data;
					response( data );
				}
			});
		}
	});
	// Big form
	$( "#frm-searchForm-form #frmform-query" ).autocomplete({
		minLength: 2,
		source: function(request, response) {
			if ( request.term in cache ) {
				response( cache[ request.term ] );
				return;
			}

			$.ajax({
				url: "/book/suggest/",
				dataType: "json",
				data: request,
				success: function( data ) {
					cache[ request.term ] = data;
					response( data );
				}
			});
		}
	});
});