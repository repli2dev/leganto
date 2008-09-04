<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/
/**
* Trida, ktera pracuje s tabulkou urcenou pro ukladani starych wiki informaci o knihach.
* @package reader
*/
class WikiOld extends MySQLTableWikiOld {
	

	/**
	* @var string Nazev tabulky v databazi
	*/
	const TABLE_NAME = "wikiOld";

	/**
	* @var array_string Nazvy poli tabulku v databazi, ktera se vzdy musi vyplnit.
	*/
	public static $importantColumns = array("book");
	
	
	/**
	 * Vytvori wiki informaci v tabulce odlozenych wiki informaci na zaklade ID wiki informace.
	 * @param int
	 */
	public static function create($id) {
		$wiki = Wiki::getInfo($id);
		parent::create(array(
			"id" => $wiki->id,
			"book" => $wiki->book,
			"text" => $wiki->text,
			"isbn" => $wiki->isbn,
			"image" => $wiki->image
		));
	}
	
	/**
	* Vrati bezne pozadovane polozky pro SQL dotaz.
	* @return string
	*/
	protected static function getCommonItems() {
		return "*";
	}

	/**
	* Vrati bezne pripojovani tabulek v SQL dotazech.
	* @return string
	*/
	protected static function getCommonJoins() {
		return "";
	}
	
	/**
	* Vytvori tabulku v databazi.
	* @return void
	*/
	public static function install() {
		$sql = "CREATE TABLE ".self::getTableName()." (
   			id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
			book INT(25),
			text TEXT DEFAULT '',
			isbn TEXT DEFAULT '',
			image ENUM('yes','no') DEFAULT 'no',
			date TIMESTAMP,
			FOREIGN KEY (book) REFERENCES ".Book::getTableName()."(id)
		)";
		MySQL::query($sql,__FILE__,__LINE__);
	}
}
?>