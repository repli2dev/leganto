<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/
/**
* Trida, ktera pracuje s tabulkou urcenou pro ukladani uzivatelske literarni tvorby.
* @package reader
*/
class Creation extends MySQLTableCreation {

	/**
	 * Vytvori tabulku v databazi.
	 * @return void
	 */
	public static function install() {
		$sql = "CREATE TABLE ".self::getTableName()." (
			id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
			user INT(25) NOT NULL,
			title VARCHAR 256 NOT NULL,
			link VARCHAR 256 DEfAULT '',
			annotation TEXT NOT NULL,
			content DEFAULT '',
			discussion INT(25) DEFAULT 0,
			FOREIGN KEY (user) REFERENCES ".Tag::getTableName()."(id)
		)";
	}
}
?>