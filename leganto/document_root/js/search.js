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
		// In mozilla, hiding is problem
		if ($.browser.mozilla) {
			$(this).css("visibility","hidden");
		}
		$(this).css("position","absolute");
		$(this).css("top","-100px");
		$(this).css("height","0px");
		$(this).css("width","0px");
		$(this).css("margin","-10px");
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

	// Nice example text
	function switchText()
	{
		if ($(this).val() == $(this).attr('title'))
			$(this).val('').removeClass('shaded-text');
		else if ($.trim($(this).val()) == '')
			$(this).addClass('shaded-text').val($(this).attr('title'));
	}

	$('input[type=text][title!=""]').each(function() {
		if ($.trim($(this).val()) == '') $(this).val($(this).attr('title'));
		if ($(this).val() == $(this).attr('title')) $(this).addClass('shaded-text');
	}).focus(switchText).blur(switchText);

	$('form').submit(function() {
		$(this).find('input[type=text][title!=""]').each(function() {
			if ($(this).val() == $(this).attr('title')) $(this).val('');
		});
	});
});