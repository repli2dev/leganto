<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/

/**
* Tato trida je urcena pro praci s tabulkou, kde jsou ulozeny informace o podobnosti uzivatelu.
* @package reader
*/
class UserSim extends MySQLTableUserSim {

	const LIST_LIMIT = 10;
	
	/**
	* @var string Nazev tabulky v databazi.
	*/
	const TABLE_NAME = "userSim";

	/**
	* @var mixed Nazvy poli tabulku v databazi, ktera se vzdy musi vyplnit. 
	*/
	protected static $importantColumns = array("owner","user","similarity");	
	
	/**
	 * Vrati Podobnost prihlaseneho uzivatele s danym uzivatelem.
	 * @param int ID uzivatele.
	 * @return int
	 */
	public static function get($usID) {
		$owner = Page::session("login");
		$sql = "SELECT similarity FROM ".self::getTableName()." WHERE owner = $owner-> AND user = $usID";
		$res = MySQL::query($sql,__FILE__,__LINE__);		
		$record = mysql_fetch_object($res);
		return $record->similarity;		
	} 		
	
	/**
	 * Vytvori tabulku v databazi.
	 * @return void
	 */
	public static function install() {
 		$sql = "CREATE TABLE ".self::getTableName()." (
 			id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
 			owner INT(25) NOT NULL,
 			user INT(25) NOT NULL,
 			similarity INT(3) NOT NULL,
 			FOREIGN KEY (owner) REFERENCES ".User::getTableName()." (id),
 			FOREIGN KEY (user) REFERENCES ".User::getTableName()." (id)
 		)";
 		MySQL::query($sql,__FILE__,__LINE__);
	}
	
 	/**
 	Spocita podobnost mezi uzivateli a zapise ji do tabulky.
 	@return void
 	*/
 	public static function updateAll() {
 		$sql = "TRUNCATE TABLE ".self::getTableName();
 		MySQL::query($sql,__FILE__,__LINE__);
 		$sql = "
 			SELECT
 				".User::getTableName().".id AS owner
 			FROM ".User::getTableName()."
 			INNER JOIN ".Opinion::getTableName()." ON ".User::getTableName().".id = ".Opinion::getTableName().".user
 			GROUP BY owner 
 			HAVING count(".Opinion::getTableName().".id) > 9
 			ORDER BY owner";
 		$res = MySQL::query($sql,__FILE__,__LINE__);
 		while($record = mysql_fetch_object($res)) {
			$owner = $record->owner;
			$sql = "
				SELECT
					".User::getTableName().".id AS usID,
					(
						SELECT
						AVG(IF(".BookSim::getTableName().".similarity IS NULL,0,".BookSim::getTableName().".similarity))
						FROM ".BookSim::getTableName()."
						INNER JOIN ".Opinion::getTableName()." AS opTable1 ON ".BookSim::getTableName().".book = opTable1.book
						INNER JOIN ".Opinion::getTableName()." AS opTable2 ON ".BookSim::getTableName().".bookFriend = opTable2.book
						WHERE
						opTable1.user = usID
						AND
						opTable2.user = $owner
					)*1000 AS similarity
				FROM ".User::getTableName()."
				INNER JOIN ".Opinion::getTableName()." ON ".User::getTableName().".id = ".Opinion::getTableName().".user
				WHERE ".User::getTableName().".id != $owner
				GROUP BY usID
				ORDER BY similarity DESC
				LIMIT 0,".self::LIST_LIMIT."		 
			";
			$res2 = MySQL::query($sql,__FILE__,__LINE__);
			while ($record2 = mysql_fetch_object($res2)) {
				if ($record2->similarity) { 
					MySQL::insert(self::getTableName(),array("owner" => $owner, "user" => $record2->usID,"similarity" => $record2->similarity));
				}
			}
 		}
 	}	
	
}
?>