/**
 * Applet for using jquery rating plugin
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
	// Firstly set a to <p> ID to be accesible only one select (with rating)
	$("#frmaddOpinionForm-rating").parent().attr("id","ratings");
	// Create target element for onHover titles
	$caption = $("<span class=\"text-rating\">&nbsp;</span>");

	$("#ratings").stars({
		inputType: "select",
		captionEl: $caption // point to our newly created element
	});

	// Make it available in DOM tree
	$caption.appendTo("#ratings");
	$("#ratings").append("&nbsp;");
});