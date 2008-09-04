<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/
/**
* Trida, ktera pracuje s tabulkou urcenou pro ukladani wiki informaci o knihach
* @package reader
*/
class Wiki extends MySQLTableWiki {

	const LIST_LIMIT = 50;
	
	/**
	* @var string Nazev tabulky.
	*/
	const TABLE_NAME = "wiki";

	/**
	* @var mixed Nazvy poli tabulku v databazi, ktera se vzdy musi vyplnit.
	*/
	public static $importantColumns = array("book");

	/**
	 * Prida ke knize obrazek.
	 * @param FILES_array Nahravany obrazek
	 * @return void
	 */
	public static function addImage($book,$image) {
		try {
			Piko::setDirectory("image/wiki/");
			if (!Piko::work($image,$book.".jpg")) {
				throw new Error(Lng::WRONG_IMG_TYPE);
			}
			$sql = "
				UPDATE ".self::getTableName()."
				SET image = 'yes'
				WHERE book = $book
			";
			MySQL::query($sql,__FILE__,__LINE__);
		}
		catch (Error $e) {
			$e->scream();
		}
	}
	
	/**
	 * Odsouhlasi wiki informaci u knihy.
	 * @param int ID wiki informace.
	 */
	public static function allow($id) {
		$new  = self::getInfo($id);
		$old = self::getInfoByCond(array(
			0 => self::getTableName().".book = $new->book",
			"AND" => self::getTableName().".allowed = 'yes'"
		));
		if (($new->book = $old->book) and $id) {
			WikiOld::create($old->id);
			Wiki::destroy($old->id);
		}
		Wiki::change($id,array("allowed" => TRUE));
	}

	/**
	 * Vytvori wiki informaci ke knize.
	 * @param int ID knihy.
	 * @param string Text informace.
	 * @param string ISBN.
	 */
	public static function create($book,$text,$isbn) {
		$owner = Page::session("login");
		if ($owner->id) {
			parent::create(array(
				"book" => $book,
				"text" => $text,
				"isbn" => $isbn
			));
		}
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
	 * Vrati wiki informaci k dane knize.
	 * @param int ID knihy
	 * @return record
	 */
	public static function getByBook($book) {
		return self::getInfoByCond(array(0 => self::getTableName().".book = $book", "AND" => self::getTableName().".allowed = 'yes'"));
	}
	
	/**
	 * Vrati jeste neposouzene wiki informace.
	 * @return mysql_result
	 */
	public static function getNotAllowed() {
		return self::makeCommonQuery(array(0 => self::getTableName().".allowed = 'no'"),self::getTableName().".date",0,self::LIST_LIMIT);
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
			allowed ENUM('yes','no') DEFAULT 'no',
			FOREIGN KEY (book) REFERENCES ".Book::getTableName()."(id)
		)";
		MySQL::query($sql,__FILE__,__LINE__);
	}
}
?>
