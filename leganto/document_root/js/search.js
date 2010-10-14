/**
 * Search applet for replacing ugly looking radios to more pleasant form
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
$(function(){
	// First hide radios (not done by css - it should work in non JS browsers)
	$('#search input[type=radio]').each(function() {
		$(this).css("visibility","hidden");
	});
	// Highlight the default item (the first one)
	$("#search label").first().attr("id","selected-search-option");
	// Triger selection by mouse
	$("#search label").click(function() {
		// Unset all others
		$('#search label').each(function() {
			$(this).attr("id", "");
		});
		// Set this one as current
		$(this).attr("id", "selected-search-option");
	});
});