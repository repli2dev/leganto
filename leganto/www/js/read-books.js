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
	HTML.push('<div class="leganto-reader">Čtenář: ' + user.nick + ' (' + user.numberOfOpinions + ' názorů)</div>');
	HTML.push('<ul>');
	for (var i=0; i<opinions.length; i++){
		HTML.push('<li><span class="cover"><img src=' + service.domain + opinions[i].image + ' alt=' + opinions[i].bookTitle + ' /></span><span class="title"><a href="' + service.domain + service.bookLink.replace('ID',opinions[i].bookTitleId) + '">' + opinions[i].bookTitle + '</a><br /><span class="author">' + opinions[i].author + '</span><img src="' + service.domain + '/img/rating_' + opinions[i].rating + '.png" alt="Hodnocení"/></span><div class="leganto-cleaner">&nbsp;</div></li>');
	}
	HTML.push('</ul>');
	HTML.push('<div class="leganto-more">Objevte více o tomto <a href="' + service.domain +service.userLink.replace('ID',user.id) + '">uživateli</a>.</div>');
	document.getElementById('leganto-content').innerHTML = HTML.join('');

}