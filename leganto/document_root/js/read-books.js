/**
 * Applet for latest opinions of user, not using jQuery to be as small as possible
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
function legantoFillData(user,opinions,service) {
	var HTML = [];
	HTML.push('<div class="">Čtenář: ' + user.nick + ' (' + user.numberOfOpinions + ' názorů)</div>');
	HTML.push('<ul>');
	for (var i=0; i<opinions.length; i++){
		HTML.push('<li><a href="' + service.domain + service.bookLink.replace('ID',opinions[i].bookTitleId) + '">' + opinions[i].bookTitle + '</a></li>');
	}
	HTML.push('</ul>');
	document.getElementById('leganto-content').innerHTML = HTML.join('');

}