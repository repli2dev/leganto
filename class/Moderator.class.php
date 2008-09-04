<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* Trida slouzici pro moderatorske akce.
* @package eskymoFW
*/
class Moderator {

	/**
	 * Zmeni udaje o knize.
	 * @param int ID knihy.
	 * @param string Nazev knihy.
	 * @param string Jmeno autora.
	 * @return void
	 */
	public static function bookChange($id,$title,$writer) {
		self::control();
		Book::change($id,$title,$writer);
	}
	
	/**
	 * Zjisti, zda ma prihlaseny uzivatel prava k moderatorskym akcim. Pokud nema, ukonci aplikaci. Tuto metodu by mely zavolat vsechny dalsi metody tehle tridy na zacatku deklarace.
	 * @return void
	 */
	public static function control() {
		$owner = Page::session("login");
		if ($owner->level < User::LEVEL_MODERATOR) {
			die(Lng::ACCESS_DENIED);
		unset($owner);
		}
	}
	
	/**
	 * Zmeni udaje o klicovem slovu.
	 * @param int ID klicoveho slova.
	 * @param string Nove zneni klicoveho slova.
	 */
	public static function tagChange($id,$tag) {
		self::control();
		Tag::change($id,$tag);
	}
	
	
}
?>