<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/
/**
* Trida, ktera pracuje s tabulkou urcenou pro ukladani nazoru na knihy.
* @package reader
*/
class Readlist extends MySQLTableReadList {

	/**
	* @var string Nazev tabulky v databazi.
	*/
	const TABLE_NAME = "readlist";

	/**
	 * Pocet zobrazenych polozek na stranku/v seznamu.
	 */
	const LIST_LIMIT = 50;
	
	/**
	* @var mixed Nazvy poli tabulku v databazi, ktera se vzdy musi vyplnit. 
	*/
	public static $importantColumns = array("user","book");
        
	/**
	* Vytvori zaznam v databazi.
	* 
	* @param string ID knihy
	* @return void
	*/
	public static function create($bookID) {
		try {
			if (!$bookID) throw new Error(Lng::WITHOUT_BOOK_ID);
			$owner = Page::session("login");
			$sql = "SELECT id FROM ".self::getTableName()." WHERE user = $owner->id AND book = $bookID ";
			$res = MySQL::query($sql,__FILE__,__LINE__);
			$op = mysql_fetch_object($res);
			if ($op->id) {
				throw new Error(Lng::ALREADY_IN_READLIST);
			}
			$input = array(
				"user" => $owner->id,
				"book" => $bookID,
			);
			parent::create($input);
		}
		catch(Error $e) {
			$e->scream();
		}
	}
        
	/**
	* Smaze zaznam v databazi.
	* 
	* @param string ID knihy
	* @return void
	*/
	public static function destroy($bookID) {
		try {
			if (!$bookID) throw new Error(Lng::WITHOUT_BOOK_ID);
			$owner = Page::session("login");
			$sql = "SELECT id FROM ".self::getTableName()." WHERE user = $owner->id AND book = $bookID ";
			$res = MySQL::query($sql,__FILE__,__LINE__);
			$op = mysql_fetch_object($res);
			if (!$op->id) {
				throw new Error(Lng::NOT_IN_READLIST);
			}
			$cond = array(
				0 => "book = '".$bookID."' AND user = '".$owner->id."'"
			);
			parent::destroyByCond($cond);
		}
		catch(Error $e) {
			$e->scream();
		}
	}

	/**
	* Vrati bezne pozadovane polozky pro SQL dotaz.
	* @return string
	*/
	public static function getCommonItems() {
		return "
			".self::getTableName().".user AS userID,
			".self::getTableName().".book AS id
		";
	}

	/**
	* Vrati bezne pripojovani tabulek v SQL dotazech.
	* @return string
	*/
	public static function getCommonJoins() {
		return "";
	}
        
	/**
	* Vrati nazory na danou knihu.
	* @param int ID knihy
	* @return mysql_result
	*/
	public static function getListByBook($book) {
		$cond = array(0 => Book::getTableName().".id = $book");
		return self::makeCommonQuery($cond,User::getTableName().".name",0,1000);
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
	* Zjisti, zda ma prihlaseny uzivatel knihu ve svem ctenarskem deniku (v tom pripade vrati ID nazoru)
	* @param int ID knihy
	* @return boolean
	*/
	public static function isToRead($bookID) {
		$owner = Page::session("login");
		$sql = "SELECT id FROM ".self::getTableName()." WHERE book = $bookID AND user = ".$owner->id;
		$res = MySQL::query($sql,__FILE__,__LINE__);
		if (mysql_num_rows($res) > 0) {
			return TRUE;
		}
		else return FALSE;
	}
        
	/**
	 * Vrati knihy uzivatele.
     * @param int ID uzivatele
	 * @param string Nazev polozky, podle ktere se maji knihy seradit.
	 * @param int Cislo stranky.
	 * @return book_mysql_query
	 */
	public static function byUser($usID,$order,$page) {
		if (empty($order)) {
			$order = "rating DESC";
		}
		if (empty($page)) {
			$page = 0;
		}
		$sql = "
			SELECT
				".Book::getTableName().".id AS id,
				".Writer::getTableName().".id AS writerID,
				".Writer::getTableName().".name AS writerName,
				ROUND(AVG(".Opinion::getTableName().".rating)) AS rating,
				".Book::getTableName().".title AS title
			FROM ".self::getTableName()."
			LEFT JOIN ".Book::getTableName()." ON ".Readlist::getTableName().".book = ".Book::getTableName().".id
			LEFT JOIN ".Writer::getTableName()." ON ".Book::getTableName().".writer = ".Writer::getTableName().".id
			LEFT JOIN ".Opinion::getTableName()." ON ".Readlist::getTableName().".book = ".Opinion::getTableName().".book
			WHERE ".self::getTableName().".user = $usID
			GROUP BY ".Book::getTableName().".id
			ORDER BY $order
			LIMIT ".($page*self::LIST_LIMIT).",".self::LIST_LIMIT."			
		";
		return MySQL::query($sql,__FILE__,__LINE__);
	}
	
	/**
	 * Vrati pocet knizek v toReadlistu
	 * @param integer userID
	 */
	public static function count($usID){
		$sql = "
			SELECT
				".self::getTableName().".id
			FROM ".self::getTableName()."
			WHERE ".self::getTableName().".user = $usID 			
		";
		$result = MySQL::query($sql,__FILE__,__LINE__);
		return mysql_num_rows($result);
	}
}
