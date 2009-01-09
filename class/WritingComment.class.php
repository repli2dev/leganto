<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/

/**
* Tato trida je urcena pro praci s tabulkou, kde jsou ulozeny diskuzni prispevky.
* @package reader
*/
class WritingComment extends MySQLTableWritingComment {
	
	/**
	* @var string Nazev tabulky
	*/
	const TABLE_NAME = "writingComment";

	/**
	* @var int Pocet zobrazovanych diskusnich prispevku (na prvni urovni)
	*/
 	const LIST_LIMIT = 20;
 	
	/**
	* @var int Pocet vracenych polozek u mensich seznamu.
	*/
	const SMALL_LIST_LIMIT = 10;
	
	/**
	* @var array_string Nazvy poli tabulku v databazi, ktera se vzdy musi vyplnit.
	*/
	private static $importantColumns = array("user","text","date");

	/**
	 * Vytvori diskuzni prispevek v databazi
	 * @param int ID zapisku, ke kteremu se komentar priklada
	 * @param string Obsah prispevku
	 * @param string predmet prispevku
	 * @param int ID rodicovskeho prispevku
	 */
 	public static function create($writing,$text,$title = NULL,$parent = NULL) {
 		$owner = Page::session("login");
  		try { 
	   		if (!$owner->id) throw new Error(Lng::ACCESS_DENIED);
			if (!$writing) throw new Error(Lng::ACCESS_DENIED);
	   		if (!$text) throw new Error(Lng::DISCUSS_WITHOUT_TEXT);
	   		if (empty($parent)) {
	   			$parent = 0;
	   		}
			if (empty($title)) {
				$title = "";
			}
	   		return parent::create(array("writing" => $writing, "text" => $text,"title" => $title, "user" => $owner->id,"parent" => $parent,"date" => "now()"));
  		}
  		catch (Error $exception) {
	   		$exception->scream();
  		}    
 	}
 	
 	/**
 	 * Odstrani prispevek
 	 * @param int ID prispevku
 	 * @return void
 	 */
 	public static function destroy($id) {
 		$owner = Page::session("login");
 		$dis = self::getInfo($id);
		try {
	 		if (($owner->level <= User::LEVEL_COMMON) && ($dis->userID != $owner->id)) {
	 			throw new Error(Lng::ACCESS_DENIED);
	 		}
			parent::destroy($id);
			//smazat závisející komentáře
			if(self::isParent($id)){
				$res = self::readAll($id);
				while($row = mysql_fetch_object($res)){
					self::destroy($row->id);
				}
			}
	 		return TRUE;
		}
		catch (Error $e) {
			$e->scream();
		}
 	}
 	
	/**
	* Vrati bezne pozadovane polozky pro SQL dotaz.
	* @return string
	*/	
 	protected static function getCommonItems() {
 		return "
	    		".self::getTableName().".id AS id,
	    		".self::getTableName().".parent AS parent,
	    		".self::getTableName().".title AS title,
	    		".self::getTableName().".text AS text,
	    		".self::getTableName().".date AS date,
	    		".User::getTableName().".id AS userID,
	    		".User::getTableName().".name AS userName
 		";
 	}
 	
	/**
	* Vrati bezne pripojovani tabulek v SQL dotazech.
	* @return string
	*/
	protected static function getCommonJoins() {
		return "
			LEFT JOIN ".User::getTableName()." ON ".self::getTableName().".user = ".User::getTableName().".id
		";
	}
 	
	/**
	* Vytvori tabulku v databazi.
	* @return void
	*/ 
 	public static function install() {
  		$sql = "CREATE TABLE ".self::getTableName()." (
					id INT(25) UNSIGNED NOT NULL AUTO_INCREMENT ,
					user INT(25) NOT NULL ,
					writing INT(25) UNSIGNED NOT NULL ,
					parent INT(25) UNSIGNED NOT NULL ,
					title VARCHAR( 256 ) NOT NULL ,
					text TEXT NOT NULL ,
					date DATETIME NOT NULL ,
				 PRIMARY KEY (id),
				 FOREIGN KEY (user) REFERENCES ".User::getTableName()." (id)
  		)";
  		MySQL::query($sql,__FILE__,__LINE__);
 	}
 	
 	/**
 	 * Vrati nejaktualnejsi diskuse.
 	 */
 	public static function lastCommentedWriting($limit) {
 		$owner = Page::session("login");
		$sql = "
 			SELECT
				".self::getTableName().".id,
				".Writing::getTableName().".title,
				".self::getTableName().".date,
				".Writing::getTableName().".id AS writingID
			FROM ".self::getTableName()."
			INNER JOIN ".Writing::getTableName()." ON ".self::getTableName().".writing = ".Writing::getTableName().".id
			WHERE 1 
			ORDER BY ".self::getTableName().".date DESC
			LIMIT 0,".$limit."
 		";
 		return MySQL::query($sql,__FILE__,__LINE__);
 	}
 	
 	/**
 	 * Vrati seznam prispevku v diskusi.
 	 * @param int Cislo zobrazovane stranky.
 	 * @return mysql_result
 	 */
 	public static function read($writing,$parent,$page,$order = "date ASC") {
 		if (empty($parent)) {
 			$parent = 0;
 		}
 		if (empty($order)) {
 			$order = "date ASC";
 		}
  		$limit = $page*(self::LIST_LIMIT);
  		$sql = "
	   		SELECT
				".self::getCommonItems()."
	   		FROM
	    		".self::getTableName()."
	    	".self::getCommonJoins()."
	    	WHERE
  				".self::getTableName().".writing = ".$writing."
			AND
				".self::getTableName().".parent = ".$parent."
	   		GROUP BY ".self::getTableName().".id
	   		ORDER BY $order
	   		LIMIT $limit,".self::LIST_LIMIT
  		;
		return MySQL::query($sql,__FILE__,__LINE__);
 	}

	/**
	 * Precte vsechny polozky z DB - dulezita funkce pro mazani
	 * @param int ID - poutije se jako parent
	 */
	public static function readAll($parent,$writing = NULL){
		if($writing){
			$cond = " AND ".self::getTableName().".writing = ".$writing;
		}
		$sql = "
	   		SELECT
				".self::getCommonItems()."
	   		FROM
	    		".self::getTableName()."
	    	".self::getCommonJoins()."
	    	WHERE
				".self::getTableName().".parent = ".$parent."
				".$cond
  		;
		return MySQL::query($sql,__FILE__,__LINE__);
	}


	/**
	 * Overi jestli je prispevek rodicem
	 * @param int ID diskuzniho prispevku
	 * @return boolean 
	 */
	public static function isParent($id){
		$sql = "
				SELECT ".self::getTableName().".id
				FROM ".self::getTableName()."
				WHERE ".self::getTableName().".parent = ".$id."
			   ";
		$res = MySQL::query($sql,__FILE__,__LINE__);
		if(mysql_num_rows($res) > 0){
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	* Zjisti, jestli komentar patri prihlasenemu uzivateli
	* @param int ID komentare
	* @return boolean
	*/
	public static function isMine($commentID) {
			$owner = Page::session("login");
			$sql = "SELECT id FROM ".self::getTableName()." WHERE id = $commentID AND user = ".$owner->id;
			$res = MySQL::query($sql,__FILE__,__LINE__);
			if (mysql_num_rows($res) > 0) {
				$result = mysql_fetch_object($res);
				return $result->id;
			}
			else return FALSE;
	}
}
?>