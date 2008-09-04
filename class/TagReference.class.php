<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/
/**
* Trida, ktera pracuje s tabulkou urcenou pro vztahu mezi knihami a klicovymi slovy.
* @package reader
*/
class TagReference extends MySQLTableTagReference {

	/**
	* @var string Nazev tabulky v databazi
	*/
	const TABLE_NAME = "tagReference";

	/**
	* @var array_string Nazvy poli tabulku v databazi, ktera se vzdy musi vyplnit.
	*/
	public static $importantColumns = array("tag","book");			
	
	/**
	* Spoji dve klicova slova.
	* @param int ID prvniho klicoveho slova (toto zanika).
	* @param int ID druheho klicoveho slova (toto zustava).
	* @return void
	*/
	public static function connect($startTag,$finishTag) {
		$sql = "UPDATE ".self::getTableName()." SET tag = $finishTag WHERE tag = $startTag";
		MySQL::query($sql,__FILE__,__LINE__);
		Tag::destroy($startTag);
	} 

	/**
	* Vytvori klicova slovo a spojeni mezi temito klicovymi slovem a knihou.
	* @param string Klicova slova oddelena carkou.
	* @param int ID knihy.
	* @return void
	*/
	public static function create($tag,$book) {
		$trans = array(", " => ",");
		$tag = strtr($tag,$trans);
		$tag = explode(",",$tag);
		foreach($tag as $tagName) {
			$tagID = Tag::create($tagName);
			$sql = "SELECT id FROM ".self::getTableName()." WHERE book = $book AND tag = $tagID";
			$res = MySQL::query($sql,__FILE__,__LINE__);
			$record = mysql_fetch_object($res);
			if (!$record->id) {
				parent::create(array("tag" => $tagID, "book" => $book));
			}
		}
	}

	/**
	* Vytvori tabulku v databazi.
	* @return void
	*/
	public static function install() {
		$sql = "CREATE TABLE ".self::getTableName()." (
			id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
			tag INT(25) NOT NULL,
			book INT(25) NOT NULL,
			FOREIGN KEY (tag) REFERENCES ".Tag::getTableName()." (id),
			FOREIGN KEY (book) REFERENCES ".Book::getTableName()." (id)
		)";
  		MySQL::query($sql,__FILE__,__LINE__);
	}
}
?>