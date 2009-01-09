<?php
/**
 * @package		reader
 * @author		Jan Papousek
 * @copyright	Internetovy ctenarsky denik
 * @link			http://ctenari.cz
 */

/**
 * 				Trida, ktera pracuje s tabulkou urcenou pro vztahu mezi polozkami a klicovymi slovy.
 * @package 	reader
 */
class TagReference extends MySQLTableTagReference {
	
	/**
	* @var string Nazev tabulky v databazi
	*/
	const TABLE_NAME = "tagReference";

	/**
	* @var array_string Nazvy poli tabulku v databazi, ktera se vzdy musi vyplnit.
	*/
	public static $importantColumns = array("tag","target", "type");			
	
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
	 * 			Vytvori klicova slovo a spojeni mezi temito klicovymi slovem a knihou.
	 * 
	 * @param 	string 	Klicova slova oddelena carkou.
	 * @param 	int 	ID knihy.
 	 * @return 	void
 	 */
	public static function create($tag, $target, $type="book") {
		$trans = array(", " => ",");
		$tag = strtr($tag,$trans);
		$tag = explode(",",$tag);
		foreach($tag as $tagName) {
			$tagID = Tag::create($tagName);
			$sql = "
				SELECT id
				FROM ".self::getTableName()."
				WHERE
					target = $target
				AND
					tag = $tagID
				AND
					type = '$type'
				";
			$res = MySQL::query($sql,__FILE__,__LINE__);
			$record = mysql_fetch_object($res);
			if (!$record->id) {
				parent::create(array(
					"tag" => $tagID,
					"target" => $target,
					"type" => $type
				));
			}
		}
	}

	/**
	 * 			Zrusi vsechny vztahy na zaklade polozky.
	 * 
	 * @param 	int 	ID polozky.
	 * @param	string	Typ polozky.
	 */
	public static function destroy($id,$type) {
		$sql = "
			SELECT
				".self::getTableName().".tag,
				COUNT(*) AS number
			FROM ".self::getTableName()."
			WHERE
				".self::getTableName().".target = $id
			AND
				".self::getTableName().".type = '$type'
			GROUP BY ".self::getTableName().".tag
			HAVING number = 1
		";
		$res = MySQL::query($sql,__FILE__,__LINE__);
		while ($tag = mysql_fetch_object($res)) {
			Tag::destroy($tag->tag);
		}
		$sql = "DELETE FROM ".self::getTableName()." WHERE target = $id AND type = '$type'";
		MySQL::query($sql,__FILE__,__LINE__);
	}
	
	/**
	* Vytvori tabulku v databazi.
	* @return void
	*/
	public static function install() {
		$sql = "CREATE TABLE ".self::getTableName()." (
			id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
			tag INT(25) NOT NULL,
			target INT(25) NOT NULL,
			type ENUM('book', 'competition', 'writing') NOT NULL DEFAULT 'book',
			FOREIGN KEY (tag) REFERENCES ".Tag::getTableName()." (id)
		)";
  		MySQL::query($sql,__FILE__,__LINE__);
	}
}
?>