<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/
/**
* Trida, ktera pracuje s tabulkou urcenou pro ukladani diskusnich temat.
* @package reader
*/
class Topic extends MySQLTableTopic {
	
	/**
	* @var string Nazev tabulky.
	*/
	const TABLE_NAME = "topic";

	/**
	* @var int Pocet vracenych polozek, je-li vracen seznam.
	*/
	const LIST_LIMIT = 50;

	/**
	* @var mixed Nazvy poli tabulku v databazi, ktera se vzdy musi vyplnit. 
	*/
	public static $importantColumns = array("name","access");	

	/**
	 * Vytvori tema v databazi.
	 * @param string Nazev tematu
	 */
	public static function create($name,$access) {
		$owner = Page::session("login");
		try {
			if ($owner->level != User::LEVEL_ADMIN) {
				throw new Error(Lng::ACCESS_DENIED);
			}
			$input = array(
				"name" => $name,
				"access" => $access
			);
			return parent::create($input);
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
			".self::getTableName().".name AS name,
			(SELECT COUNT(".Discussion::getTableName().".id) FROM ".Discussion::getTableName()." WHERE ".Discussion::getTableName().".type = 'topic' AND ".Discussion::getTableName().".follow = ".self::getTableName().".id) AS numDis
		";
	}

	/**
    * Vrati bezne pripojovani tabulek v SQL dotazech.
	* @return string
	*/
	protected static function getCommonJoins() {
		return "
			LEFT JOIN ".Discussion::getTableName()." ON ".self::getTableName().".id = ".Discussion::getTableName().".follow
		";
	}
	/**
	 * Vytvori tabulku v databazi.
	 * @return void
	 */
	public static function install() {
		$sql = "CREATE TABLE ".self::getTableName()." (
			id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
			name VARCHAR(64) UNIQUE NOT NULL,
			access ENUM ('1','2','3','4') DEFAULT '1'
		)";
		MySQL::query($sql,__FILE__,__LINE__);
	}
	/**
	 * Vrati vsechna temata.
	 * @param string Polozka, podle ktere maji byt temata serazena.
	 * @param int Cislo prohlizene stranky.
	 * @return unknown
	 */
	public static function read($order,$page) {
		if (empty($order)) {
			$order = "name";
		}
		if (empty($page)) {
			$page = 0;
		}
		$owner = Page::session("login");
		if (empty($owner->level)) {
			$owner->level = 1;
		}
		$page = $page*(self::LIST_LIMIT);
		$sql = "
			SELECT
				".self::getCommonItems()."
			FROM
				".self::getTableName()."
			".self::getCommonJoins()."
			WHERE
				".self::getTableName().".access <= ".$owner->level."
			GROUP BY ".self::getTableName().".id
			ORDER BY $order
			LIMIT $page,".self::LIST_LIMIT."
		";
		return MySQL::query($sql,__FILE__,__LINE__);
	}
}
?>