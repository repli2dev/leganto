<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/
/**
* Trida, ktera pracuje s tabulkou urcenou pro ukladani soukromych zprav.
* @package reader
*/
class Message extends MySQLTableMessage {

	/**
	* @var string Nazev tabulky v databazi.
	*/
	const TABLE_NAME = "message";

	const LIST_LIMIT = 50;
	
	/**
	* @var mixed Nazvy poli tabulku v databazi, ktera se vzdy musi vyplnit. 
	*/
	protected static $importantColumns = array("usTo","usFrom","content","date");

	/*
	 * Nastavi zpravu jako smazanou, pripadne ji smaze z databaze.
	 * @param int ID zpravy.
	 * @return void
	 */
	public static function destroy($id) {
		$help = FALSE;
		$mes = self::getInfo($id);
		$owner = Page::session("login");
		if ($owner->id == $mes->userIDTo) {
			if ($mes->fromDestroy == 2) {
				$sql = "DELETE FROM ".self::getTableName()." WHERE id = $id";
			}
			else {
				$sql = "UPDATE ".self::getTableName()." SET toDestroy = 2 WHERE id = $id";
			}
			MySQL::query($sql,__FILE__,__LINE__);
			$help = TRUE;
		}
		else if ($owner->id == $mes->userIDFrom) {
			if ($mes->toDestroy == 2) {
				$sql = "DELETE FROM ".self::getTableName()." WHERE id = $id";
			}
			else {
				$sql = "UPDATE ".self::getTableName()." SET fromDestroy = 2 WHERE id = $id";
			}
			MySQL::query($sql,__FILE__,__LINE__);
			$help = TRUE;
		}
		return $help; 
	}

	/**
	* Vrati bezne pozadovane polozky pro SQL dotaz.
	* @return string
	*/
	protected static function getCommonItems() {
		return "
			".self::getTableName().".id AS mesID,
			".self::getTableName().".date,
			".self::getTableName().".content,
			".self::getTableName().".usFrom AS userIDFrom,
			".User::getTableName().".name AS userNameFrom,
			".self::getTableName().".usTo AS userIDTo,
			".self::getTableName().".fromDestroy,
			".self::getTableName().".toDestroy,
			(SELECT ".User::getTableName().".name FROM ".self::getTableName()." LEFT JOIN ".User::getTableName()." ON ".self::getTableName().".usTo = ".User::getTableName().".id WHERE ".self::getTableName().".id = mesID) AS userNameTo
		
		";
	}
	/**
	* Vrati bezne pripojovani tabulek v SQL dotazech.
	*/
	protected static function getCommonJoins() {
		return "
			INNER JOIN ".User::getTableName()." ON ".self::getTableName().".usFrom = ".User::getTableName().".id
		";
	}
	
	/**
	* Create table in database.
	* @return void
	*/
	public static function install() {
		$sql = "CREATE TABLE ".self::getTableName()." (
			id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
			usTo INT(25) NOT NULL,
			usFrom INT(25) NOT NULL,
			content TEXT NOT NULL,
			isRead ENUM('1','2') DEFAULT '1',
			toDestroy ENUM('1','2') DEFAULT '1',
			fromDestroy ENUM('1','2') DEFAULT '1',
			date DATETIME default '0000-00-00 00:00:00',
			FOREIGN KEY (usTo) REFERENCES ".User::getTableName()." (id),
			FOREIGN KEY (usFrom) REFERENCES ".User::getTableName()." (id)
		)";
		MySQL::query($sql,__FILE__,__LINE__);
	}
	
	/**
	 * Vrati pocet neprectenych zprav.
	 * @return int
	 */
	public function notRead() {
		$owner = Page::session("login");
		$sql = "SELECT COUNT(id) AS notRead FROM ".self::getTableName()." AS notRead WHERE usTo = $owner->id AND isRead = 1";
		$res = MySQL::query($sql,__FILE__,__LINE__);
		$record = mysql_fetch_object($res);
		return $record->notRead;
	} 	
	
	/**
	 * Precte zpravy zasleny prihlasenym uzivatelem nebo prihlasenemu uzivateli.
	 * @param int Zobrazovana zprava.
	 * @return mysql_result
	 */
	public static function readAll($page) {
		$owner = Page::session("login");
		MySQL::update(self::getTableName(),array("isRead" => 2),array(0 => "usTo = $owner->id"));
		$sql = "
			SELECT
				".self::getCommonItems()."
			FROM
				".self::getTableName()."
			".self::getCommonJoins()."
			WHERE
			(
				".self::getTableName().".usFrom = $owner->id
				AND
				".self::getTableName().".fromDestroy = 1
			)
			OR 
			(
				".self::getTableName().".usTo = $owner->id
				AND
				".self::getTableName().".toDestroy = 1
			)
			GROUP BY ".self::getTableName().".id
			ORDER BY ".self::getTableName().".date DESC
			LIMIT ".($page*self::LIST_LIMIT).", ".self::LIST_LIMIT."
		";
		return MySQL::query($sql,__FILE__,__LINE__);
	}
	
	/**
	 * Precte zpravy odeslane prihlasenemu uzivateli.
	 * @return mysql_result
	 */
	public function readRecieved() {
		$owner = Page::session("login");
		MySQL::update(self::getTableName(),array("isRead" => 2),array(0 => "usTo = $owner->id"));
		$sql = "
			SELECT
				".self::getCommonItems()."
			FROM
				".self::getTableName()."
			".self::getCommonJoins()."
			WHERE
				".self::getTableName().".usTo = $owner->id
			AND
				".self::getTableName().".toDestroy = 1
			GROUP BY ".self::getTableName().".id
			ORDER BY ".self::getTableName().".date DESC
		";
		return MySQL::query($sql,__FILE__,__LINE__);
	} 
	
	/**
	* Precte zpravy odeslane prihlasenym uzivatelem.
	* @return mysql_result
	*/
	public static function readSent() {
		$owner = Page::session("login");
		$sql = "
			SELECT
				".self::getCommonItems()."
			FROM
				".self::getTableName()."
			".self::getCommonJoins()."
			WHERE
				".self::getTableName().".usFrom = $owner->id
			AND
				".self::getTableName().".fromDestroy = 1
			GROUP BY ".self::getTableName().".id
			ORDER BY ".self::getTableName().".date DESC
		";
		return MySQL::query($sql,__FILE__,__LINE__);
 	}

 	/**
 	* Posle uzivateli zpravu.
 	* @param string Jmeno uzivatele.
 	* @param string Obsah soukrome zpravy.
 	* @return void.
 	*/
 	public function send($userName,$content) {
		try {
			$user = User::getInfoByItem(User::getTableName().".name",$userName);
 			if (!$user->id) { 				
 				throw new Error(Lng::NO_USER_FOR_MESSAGE);
 			}
			$owner = Page::session("login");
 			if ($user->id == $owner->id) {
 				throw new Error(Lng::MESSAGE_TO_OWNER);
 			}
			parent::create(array(
				"usTo" => $user->id,
				"usFrom" => $owner->id,
				"content" => $content,
				"date" => "now()")
			);
		}
		catch(Error $e) {
			$e->scream();
		}
	}
}
?>