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
	
	const TINY_LIST_LIMIT = 5;
	
	/**
	* @var array_string Nazvy poli tabulku v databazi, ktera se vzdy musi vyplnit.
	*/
	private static $importantColumns = array("user","text","date","follow", "type");

	/**
	 * 			Vytvori diskuzni prispevek v databazi
	 * 
	 * @param 	string 	Obsah prispevku
	 * @param	int		Polozka, ke ktere se diskusni prispevek vztahuje.
	 * @param	string	Typ diskusniho prispevku, resp. vec, za kterou nasleduje ('book','discussion','topic','writing','competition')
	 * @param	string	Titulek diskusniho prispevku.
	 * @param 	int		Pripadne ID prispevku, na ktery prispevek reaguje.
	 * @return	int 	ID vytvoreneho prispevku.
	 */
 	public static function create($text,$follow,$type,$title = NULL,$parent = NULL) {
 		$owner = Page::session("login");
  		try { 
	   		//if (!$owner->id) throw new Error(Lng::ACCESS_DENIED);
			if (!$follow) throw new Error(Lng::WITHOUT_ARGUMENT."follow");
	   		return parent::create(array(
	   			"follow" => $follow,
	   			"title" => $title,
	   			"user" => $owner->id,
	   			"text" => $text,
	   			"date" => "now()",
	   			"type" => $type,
	   			"parent" => $parent
	   		));   
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
	 		$sql = "
	 			SELECT *
	 			FROM ".self::getTableName()."
	 			WHERE
	 				type = 'discussion'
	 			AND
	 				follow = ".$dis->id."
	 			OR
	 				parent = ".$dis->id."
	 		";
	 		if (mysql_num_rows(MySQL::query($sql,__FILE__,__LINE__)) > 0) {
	 			throw new Error(Lng::DISCUSSION_HAS_FOLLOWERS);
	 		}
	 		parent::destroy($id);
	 		return TRUE;
		}
		catch (Error $e) {
			$e->scream();
		}
 	}
 	
 	/**
 	 *			Odstrani z databaze vsechny prispevky, ktere nasleduji danou polozku.
 	 *
 	 * @param	int		ID polozky
 	 * @param 	string	Typ polozky
 	 */
 	public static function destroyByFollow($id,$type) {
 		try {
 			if (empty($id)) {
 				throw new Error(Lng::WITHOUT_ARGUMENT . "id");
 			}
 			if (empty($type)) {
 				throw new Error(Lng::WITHOUT_ARGUMENT . "type");
 			}
			$sql = "DELETE FROM ".self::getTableName()." WHERE follow = $id AND type = '$type'";
			MySQL::query($sql,__FILE__,__LINE__);
			return TRUE;
 		}
		catch(Error $e) {
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
	    		".self::getTableName().".follow AS follow,
	    		".self::getTableName().".title AS title,
	    		".self::getTableName().".text AS text,
	    		".self::getTableName().".date AS date,
	    		".self::getTableName().".parent AS parent,
	    		".self::getTableName().".type AS type,
	    		".User::getTableName().".id AS userID,
	    		".User::getTableName().".name AS userName,
	    		CASE
	    			WHEN type = 'discussion' THEN (SELECT help.title FROM ".self::getTableName()." AS help WHERE help.id = ".self::getTableName().".follow)
	    			WHEN type = 'book' THEN (SELECT help.title FROM ".Book::getTableName()." AS help WHERE help.id = ".self::getTableName().".follow)
	    			WHEN type = 'competition' THEN (SELECT help.name FROM ".Competition::getTableName()." AS help WHERE help.id = ".self::getTableName().".follow)
	    			WHEN type = 'writing' THEN (SELECT help.title FROM ".Writing::getTableName()." AS help WHERE help.id = ".self::getTableName().".follow)
	    			WHEN type = 'topic' THEN title    
	    		END AS topicName,
	    		CASE
	    			WHEN type = 'topic' THEN ".self::getTableName().".id
	    			ELSE ".self::getTableName().".follow    
	    		END AS topicID,
	    		CASE
	    			WHEN type = 'discussion' THEN (SELECT COUNT(*) FROM ".self::getTableName()." AS help WHERE help.follow = ".self::getTableName().".follow AND help.type = 'discussion') + 1
	    			WHEN type = 'topic' THEN (SELECT COUNT(*) FROM ".self::getTableName()." AS help WHERE help.follow = ".self::getTableName().".id AND help.type = 'discussion') + 1
	    			ELSE (SELECT COUNT(*) FROM ".self::getTableName()." AS help WHERE help.follow = ".self::getTableName().".follow AND help.type = ".self::getTableName().".type)
	    		END AS numDis,
	    		(
	    			SELECT MAX(help.date) FROM ".self::getTableName()." AS help
	    			WHERE
	    				(help.type = ".self::getTableName().".type AND help.follow = ".self::getTableName().".follow)
	    				OR
	    				(help.type = 'discussion' AND ".self::getTableName().".type = 'topic' AND help.follow = ".self::getTableName().".id)
	    		) AS lastDate
 		";
 	}
 	
	/**
	* Vrati bezne pripojovani tabulek v SQL dotazech.
	* @return string
	*/
	protected static function getCommonJoins() {
		return "
			INNER JOIN ".User::getTableName()." ON ".self::getTableName().".user = ".User::getTableName().".id
		";
	}
 	
	/**
	* Zjisti, jestli prispevek patri prihlasenemu uzivateli
	* @param int ID prispevek
	* @return boolean
	*/
	public static function isMine($id) {
			$owner = Page::session("login");
			$sql = "SELECT id FROM ".self::getTableName()." WHERE id = $id AND user = ".$owner->id;
			$res = MySQL::query($sql,__FILE__,__LINE__);
			if (mysql_num_rows($res) > 0) {
				$result = mysql_fetch_object($res);
				return $result->id;
			}
			else return FALSE;
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
	* Vytvori tabulku v databazi.
	* @return void
	*/ 
 	public static function install() {
  		$sql = "CREATE TABLE ".self::getTableName()." (
	   		id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
	   		user INT(25) NOT NULL,   
	   		follow INT(25) DEFAULT 0,
	   		title VARCHAR (256) DEFAULT '',
	   		text TEXT NOT NULL,
	   		date DATETIME default '0000-00-00 00:00:00',
	   		type ENUM('book', 'competition', 'writing', 'discussion', 'topic') NOT NULL DEFAULT 'discussion',
	   		parent INT(25) NULL,
	   		FOREIGN KEY (user) REFERENCES ".User::getTableName()." (id)
	   		
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
 		$cond = "
 			SELECT ".self::getTableName().".id
 			FROM ".self::getTableName()."
 			INNER JOIN ".Topic::getTableName()." ON ".self::getTableName().".follow = ".Topic::getTableName().".id
 			WHERE ".Topic::getTableName().".access > $owner->level
 			AND ".self::getTableName().".type = 'topic'
 			GROUP BY ".self::getTableName().".id
 		";
 		$sql = "
			SELECT
				".self::getCommonItems().",
				CASE
	    			WHEN type = 'topic' OR type = 'discussion' THEN 'discussion'
	    			ELSE ".self::getTableName().".type    
	    		END AS helpType
			FROM ".self::getTableName()."
			".self::getCommonJoins()."
			WHERE
				".self::getTableName().".id NOT IN ($cond)
 				AND
 				(
 				".self::getTableName().".follow NOT IN ($cond)
 				OR
 				".self::getTableName().".type != 'discussion'
 				)
			GROUP BY topicID,helpType
			ORDER BY lastDate DESC
			LIMIT 0,".self::SMALL_LIST_LIMIT."
 		";
 		//die($sql);
 		return MySQL::query($sql,__FILE__,__LINE__);
 	}
 	
 	/**
 	 * 			Vrati seznam prispevku v diskusi.
 	 * 
 	 * @param	int		Polozka, ke ktere se diskuse vztahuje.
 	 * @param	string	Typ polozky, ke ktere se diskuse vztahuje.	
 	 * @param 	int		Cislo zobrazovane stranky.
 	 * @param	string	Razeni.
 	 * @return 	mysql_result
 	 */
 	public static function read($follow, $type, $page = NULL, $order = "date") {
 		if ($page != NULL) {
 			$limit = "LIMIT ".($page*self::LIST_LIMIT).",".self::LIST_LIMIT;
 		}
 		else {
 			$limit = "";
 		}
 		if (empty($order)) {
 			$order = "date DESC";
 		}
 		$sql = "
 			SELECT
 				".self::getCommonItems()."
 			FROM ".self::getTableName()."
 			".self::getCommonJoins()."
 			WHERE
 				".self::getTableName().".follow = $follow
 			AND
 				".self::getTableName().".type = '$type'
 			GROUP BY ".self::getTableName().".id
 			ORDER BY $order
 			$limit
 		";
 		return MySQL::query($sql,__FILE__,__LINE__);
 	}	
 	
 	
 	/**
 	 *			Vrati seznam prispevku v diskusi, kde se zobrazuji reakce.
 	 * @param	int 	ID polozky, ke ktere se diskuse vztahuje.
 	 * @param	string	Typ polozky, ke ktere se diskuse vztahuje.
 	 * @param 	int		ID rodice.
 	 * @param 	int		Cislo zobrazovane stranky.
 	 * @return 	mysql_result
 	 */
 	public static function readResponsable($follow,$type,$parent,$page,$order = "date ASC") {
 		if (empty($parent)) {
 			$parent = 0;
 		}
 		if (empty($order)) {
 			$order = "date ASC";
 		}
 		if (empty($page)) {
 			$page = 0;
 		}
  		$limit = $page*(self::LIST_LIMIT);
  		$sql = "
	   		SELECT
				".self::getCommonItems()."
	   		FROM
	    		".self::getTableName()."
	    	".self::getCommonJoins()."
	    	WHERE
  				".self::getTableName().".follow = $follow
			AND
				".self::getTableName().".type = '$type'
			AND
				".self::getTableName().".parent = $parent
	   		GROUP BY ".self::getTableName().".id
	   		ORDER BY $order
	   		LIMIT $limit,".self::LIST_LIMIT
  		;
		return MySQL::query($sql,__FILE__,__LINE__);
 	}
}
?>