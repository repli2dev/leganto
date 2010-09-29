/**
 * Applet for using jquery autocomplete (with data filling, cache and switching based on choosed radio input)
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
$(function() {
	// Local caches
	var cache = {};
	var authorCache = {};
	var tagCache = {};

	// Small search form in header
	$( "#search #frmform-query" ).autocomplete({
		minLength: 2,
		source: function(request, response) {
			// Turn off when not searching books or authors
			if($("#search input:radio:checked").val() == "default") {
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
						response(data);
					}
				});
			} else
			if($("#search input:radio:checked").val() == "author") {
				if ( request.term in authorCache ) {
					response( authorCache[ request.term ] );
					return;
				}

				$.ajax({
					url: "/author/suggest/",
					dataType: "json",
					data: request,
					success: function( data ) {
						authorCache[ request.term ] = data;
						response( data );
					}
				});
			} else return false;
		}
	});

	// Big search form
	$( "#frm-searchForm-form #frmform-query" ).autocomplete({
		minLength: 2,
		source: function(request, response) {
			// Turn off when not searching books or authors
			if($("#search input:radio:checked").val() == "default") {
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
						response(data);
					}
				});
			} else
			if($("#search input:radio:checked").val() == "author") {
				if ( request.term in authorCache ) {
					response( authorCache[ request.term ] );
					return;
				}

				$.ajax({
					url: "/author/suggest/",
					dataType: "json",
					data: request,
					success: function( data ) {
						authorCache[ request.term ] = data;
						response( data );
					}
				});
			} else return false;
		}
	});
	// Tags (adding opinion)
	function split(val) {
		return val.split(/,\s*/);
	}
	function extractLast(term) {
		return split(term).pop();
	}
	$( "#frm-insertingOpinion-addOpinionForm #frmaddOpinionForm-tags" ).autocomplete({
		source: function(request, response) {
			if ( extractLast(request.term) in tagCache ) {
				response( tagCache[ extractLast(request.term) ] );
				return;
			}
			request.term = extractLast(request.term);
			$.ajax({
				url: "/book/tag-suggest/",
				dataType: "json",
				data: request,
				success: function( data ) {
					tagCache[ request.term ] = data;
					response( data );
				}
			});
		},
		search: function() {
			// custom minLength
			var term = extractLast(this.value);
			if (term.length < 2) {
				return false;
			}
		},
		focus: function() {
			// prevent value inserted on focus
			return false;
		},
		select: function(event, ui) {
			var terms = split( this.value );
			// remove the current input
			terms.pop();
			// add the selected item
			terms.push( ui.item.value );
			// add placeholder to get the comma-and-space at the end
			terms.push("");
			this.value = terms.join(", ");
			return false;
		}

	});
});