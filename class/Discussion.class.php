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
class Discussion extends MySQLTableDiscussion {
	
	/**
	* @var string Nazev tabulky
	*/
	const TABLE_NAME = "discussion";

	/**
	* @var int Pocet zobrazovanych diskusnich prispevku.
	*/
 	const LIST_LIMIT = 100;
 	
	/**
	* @var int Pocet vracenych polozek u mensich seznamu.
	*/
	const SMALL_LIST_LIMIT = 10;
	
	/**
	* @var array_string Nazvy poli tabulku v databazi, ktera se vzdy musi vyplnit.
	*/
	private static $importantColumns = array("user","text","date","topic","follow");

	/**
	 * Vytvori diskuzni prispevek v databazi
	 * @param string Obsah prispevku
	 */
 	public static function create($topic,$text,$follow = NULL,$title = NULL) {
 		$owner = Page::session("login");
  		try { 
	   		if (!$owner->id) throw new Error(Lng::ACCESS_DENIED);
	   		if (!$text) throw new Error(Lng::DISCUSS_WITHOUT_TEXT);
	   		if (empty($follow)) {
	   			$follow = 0;
	   		}
			if (empty($title)) {
				$title = "";
			}
	   		return parent::create(array("topic" => $topic, "follow" => $follow,"title" => $title, "user" => $owner->id,"text" => $text,"date" => "now()"));   
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
 		if (($owner->level > User::LEVEL_COMMON) or ($dis->user == $owner->id)) {
 			parent::destroy($id);
 		}
 	}
 	
	/**
	* Vrati bezne pozadovane polozky pro SQL dotaz.
	* @return string
	*/	
 	protected static function getCommonItems() {
 		return "
	    		".self::getTableName().".id AS id,
	    		".self::getTableName().".topic AS topicID,
	    		".self::getTableName().".follow AS follow,
	    		".self::getTableName().".title AS title,
	    		".self::getTableName().".text AS text,
	    		".self::getTableName().".date AS date,
	    		".User::getTableName().".id AS userID,
	    		".User::getTableName().".name AS userName,
	    		".Topic::getTableName().".name AS topicName,
	    		(SELECT COUNT(help.id) FROM ".self::getTableName()." AS help WHERE help.id = ".self::getTableName().".id OR help.follow = ".self::getTableName().".id) AS numDis,
	    		(SELECT MAX(help.date) FROM ".self::getTableName()." AS help WHERE help.id = ".self::getTableName().".id OR help.follow = ".self::getTableName().".id) AS lastDate
 		";
 	}
 	
	/**
	* Vrati bezne pripojovani tabulek v SQL dotazech.
	* @return string
	*/
	protected static function getCommonJoins() {
		return "
			LEFT JOIN ".User::getTableName()." ON ".self::getTableName().".user = ".User::getTableName().".id
			INNER JOIN ".Topic::getTableName()." ON ".self::getTableName().".topic = ".Topic::getTableName().".id
		";
	}
 	
	/**
	* Vytvori tabulku v databazi.
	* @return void
	*/ 
 	public static function install() {
  		$sql = "CREATE TABLE ".self::getTableName()." (
	   		id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
	   		user INT(25) NOT NULL,   
	   		topic INT(25) NOT NULL,
	   		follow INT(25) DEFAULT 0,
	   		title VARCHAR (256) DEFAULT '',
	   		text TEXT NOT NULL,
	   		date DATETIME default '0000-00-00 00:00:00',
	   		FOREIGN KEY (user) REFERENCES ".User::getTableName()." (id),
	   		FOREIGN KEY (topic) REFERENCES ".Topic::getTableName()." (id)
	   		
  		)";
  		MySQL::query($sql,__FILE__,__LINE__);
 	}
 	
 	/**
 	 * Vrati nejaktualnejsi diskuse.
 	 */
 	public static function last() {
 		$owner = Page::session("login");
 		if (empty($owner->level)) {
 			$owner->level = 1;
 		}
 		$sql = "
 			SELECT
				".self::getTableName().".id,
				".self::getTableName().".title,
				".self::getTableName().".topic AS topicID,
				(SELECT MAX(help.date) FROM ".self::getTableName()." AS help WHERE help.id = ".self::getTableName().".id OR help.follow = ".self::getTableName().".id) AS lastDate,
				(SELECT COUNT(help.id) FROM ".self::getTableName()." AS help WHERE help.id = ".self::getTableName().".id OR help.follow = ".self::getTableName().".id) AS numDis
			FROM ".self::getTableName()."
			INNER JOIN ".Topic::getTableName()." ON ".self::getTableName().".topic = ".Topic::getTableName().".id
			WHERE
				".self::getTableName().".follow = 0
			AND
				".Topic::getTableName().".access <= $owner->level
			ORDER BY lastDate DESC
			LIMIT 0,".self::SMALL_LIST_LIMIT."
 		";
 		return MySQL::query($sql,__FILE__,__LINE__);
 	}
 	
 	/**
 	 * Vrati seznam prispevku v diskusi.
 	 * @param int Cislo zobrazovane stranky.
 	 * @return mysql_result
 	 */
 	public static function read($topic,$follow,$page,$order = "date DESC") {
 		if (empty($follow)) {
 			$follow = 0;
 		}
 		if (empty($topic)) {
 			$topic = 0;
 		}
 		if (empty($order)) {
 			$order = "date DESC";
 		}
  		$limit = $page*(self::LIST_LIMIT);
  		$sql = "
	   		SELECT
				".self::getCommonItems()."
	   		FROM
	    		".self::getTableName()."
	    	".self::getCommonJoins()."
	    	WHERE
	    	(
	    		".self::getTableName().".follow = $follow
  			OR
  				".self::getTableName().".id = $follow
  			)
  			AND
  				".self::getTableName().".topic = $topic
	   		GROUP BY ".self::getTableName().".id
	   		ORDER BY $order
	   		LIMIT $limit,".self::LIST_LIMIT
  		;
		return MySQL::query($sql,__FILE__,__LINE__);
 	}
}
?>