/**
 * Applet for marking all external links with special symbol
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
$(document).ready(function() {
	$('a').filter(function() {
		return this.hostname && this.hostname !== location.hostname;
	}).addClass('external');
});