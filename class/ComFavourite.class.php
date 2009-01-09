<?php
require_once("./eskymofw/Autoload.class.php");

Autoload::add("");
Autoload::add("eskymofw");
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/

/**
* Tato trida je urcena pro praci s tabulkou, kde jsou ulozeny informace o oblibenych diskusich pod knihami.
* @package reader
*/
class ComFavourite extends MySQLTable {

	/**
	* @var int Pocet zobrazovanych komentaru.
	*/
 	const LIST_LIMIT = 1000;
	
	/**
	* @var string Nazev tabulky
	*/
	const TABLE_NAME = "comFavourite";

	/**
	* @var array_string Nazvy poli tabulku v databazi, ktera se vzdy musi vyplnit.
	*/
	private static $importantColumns = array("user","book");
	
	/**
	* Vytvori zaznam o oblibenosti diskuse k dane knize (vztazenou k prihlasenemu uzivateli).
	* @param int ID knihy.
	* @return void
	*/
 	public function create($bookID) {
  		$owner = Page::session("login");
		parent::create(array("user" => $owner->id,"book" => $bookID));
		
 	}

	/**
	* Znici zaznam o oblibenosti diskuse k dane knize (vztazene k prihlasenemu uzivateli).
	* @param int ID knihy.
	* @return void
	*/
 	public static function destroy($bookID) {
  		$owner = Page::session("login");
		self::destroyByCond(array(0 => "book = $bookID", "AND" => "user = $owner->id"));
 	}

	/**
	* Vrati oblibene diskuse ke kniham daneho uzivatele.
	* @param int ID uzivatele.
	* @return mysql_result
	*/
 	public function getByUser($usID) {
		//------------- NENI HOTOVE ------------------------------------
 	} 	 
 	
 	/**
 	* Vytvori tabulku v databazi.
 	* @return void
 	*/
 	public static function install() {
  		$sql = "CREATE TABLE ".self::getTableName()." (
	   		id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
	   		user INT(25) NOT NULL,
	   		book INT(25) NOT NULL,
	   		FOREIGN KEY (user) REFERENCES ".User::getTableName()." (id),
	   		FOREIGN KEY (book) REFERENCES ".Book::getTableName()." (id)
  		)";
		MySQL::query($sql,__FILE__,__LINE__);
 	}

 	/**
 	* Vrati TRUE, pokud ma prihlaseny uzivatel diskusi k dane knize jako oblibenou.
 	* @param int ID knihy.
 	* @return boolean
 	*/
 	public function isFavourite($bookID) {
  		$owner = Page::session("login");
  		$sql = "SELECT id FROM ".self::getTableName()." WHERE user = $owner->id AND book = $bookID";
  		$res = MySQL::query($sql,__FILE__,__LINE__);
  		if (mysql_num_rows($res) > 0) return TRUE;
  		else return FALSE;
 	}
}
?>