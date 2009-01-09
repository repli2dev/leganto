<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/

/**
* Trida, ktera pracuje s tabulkou urcenou pro ukladani dat o podobnosti knih.
* @package reader
*/
class BookSim extends MySQLTableBookSim {
	
	/**
	* @var string Nazev tabulky v databazi.
	*/
	const TABLE_NAME = "bookSim";

	/**
	* @var mixed Nazvy poli tabulku v databazi, ktera se vzdy musi vyplnit. 
	*/
	private static $importantColumns = array("book","bookFriend","bookFriend");	
	
	/**
	 * Vytvori tabulku v databazi.
	 * @return void
	 */
	public static function install() {
 		$sql = "CREATE TABLE ".self::getTableName()." (
 			id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
 			book INT(25) NOT NULL,
 			bookFriend INT(25) NOT NULL,
 			similarity INT(3) NOT NULL,
 			FOREIGN KEY (book) REFERENCES ".Book::getTableName()." (id),
 			FOREIGN KEY (bookFriend) REFERENCES ".Book::getTableName()." (id)
 		)";
 		MySQL::query($sql,__FILE__,__LINE__);
	}
	
	/**
 	Spocita podobnost mezi knihami a zapise ji do tabulky.
 	@return void
 	*/
 	public static function updateAll() {
 		$sql = "TRUNCATE TABLE ".self::getTableName();
 		MySQL::query($sql,__FILE__,__LINE__);
 		$sql = "SELECT id FROM ".Book::getTableName();
 		$res = MySQL::query($sql,__FILE__,__LINE__);
 		while($record = mysql_fetch_object($res)) {
			$sql = "
				SELECT
					".Book::getTableName().".id,
	           		(SELECT COUNT(id) FROM ".TagReference::getTableName()." WHERE tag IN (SELECT tag FROM ".TagReference::getTableName()." WHERE target = $record->id AND type='book') AND target = ".Book::getTableName().".id AND type='book')
					AS similarity
				FROM
					".Book::getTableName()."
				WHERE ".Book::getTableName().".id != $record->id
				GROUP BY ".Book::getTableName().".id
				ORDER BY ".Book::getTableName().".id
			";
			$res2 = MySQL::query($sql,__FILE__,__LINE__);
			while ($record2 = mysql_fetch_object($res2)) {
				if ($record2->similarity) {
					MySQL::insert(self::getTableName(),array(
						"book" => $record->id,
						"bookFriend" => $record2->id,
						"similarity" => $record2->similarity
					));
				}
			}
 		}
 	}	
}
?>