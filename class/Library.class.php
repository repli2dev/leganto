<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/
/**
* Trida, ktera pracuje s tabulkou urcenou pro ukladani vyhledavani v knihovnach.
* @package reader
*/
class Library extends MySQLTableLibrary {
        
        /**
        * @var string Nazev tabulky.
        */
        const TABLE_NAME = "library";

        /**
         * Retezec, ktery se v odkazu nahradi za hledany vyraz.
         * @var string
         */
        const CHANGE = "***CHANGE***";
        
        /**
        * @var int Pocet vracenych polozek, je-li vracen seznam.
        */
        const LIST_LIMIT = 50;

        /**
        * @var mixed Nazvy poli tabulku v databazi, ktera se vzdy musi vyplnit. 
        */
        public static $importantColumns = array("name","link");

        /**
        * @var int Pocet vracenych polozek u mensich seznamu.
        */
        const SMALL_LIST_LIMIT = 10;

        /**
         * Vytvori polozku v databazi.
         * 
         * @param mixed Vstup ("name","link").
         * @return int ID polozky
         */
        public static function create($input) {
        	try {
        		$owner = Page::session("login");
        		if ($owner->level < User::LEVEL_ADMIN) {
        			throw new Error(Lng::ACCESS_DENIED);
        		}
        		parent::create($input);
        	}
        	catch (Error $e) {
        		$e->scream();
        	}
        }
        
        /**
         * Vrati vsechny knihovny, ve kterych se vyhledava.
         * 
         * @return mysql_result
         */
        public static function getAll() {
        	return self::makeCommonQuery(array(),self::getTableName().".name",0,1000);
        }
        
        /**
         * Vrati knihovny ve, kterych chce dany uzivatel vyhledavat.
         * 
         * @param int ID uzivatele
         * @return mysql_result
         */
        public static function getByUser($userID) {
        	if (!empty($userID)) {
	        	$sql = "
	        		SELECT
	        			".self::getCommonItems()."
	        		FROM ".self::getTableName()."
	        		".self::getCommonJoins()."
	        		WHERE ".LibraryMenu::getTableName().".user = $userID
	        		GROUP BY ".LibraryMenu::getTableName().".id
	        	";
	        	return MySQL::query($sql,__FILE__,__LINE__);
        	}
        	else {
        		return NULL;
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
                	".self::getTableName().".link AS link,
                	".self::getTableName().".ascii AS ascii
                ";
        }

        /**
        * Vrati bezne pripojovani tabulek v SQL dotazech.
        * @return string
        */
        protected static function getCommonJoins() {
                return "
                	LEFT JOIN ".LibraryMenu::getTableName()." ON ".self::getTableName().".id = ".LibraryMenu::getTableName().".library
                ";
        }
  
        /**
        * Vytvori tabulku v databazi.
        * @return void
        */
        public static function install() {
                $sql = "CREATE TABLE ".self::getTableName()." (
                        id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
                        name VARCHAR(250),
                        link VARCHAR(250),
                        ascii ENUM('yes','no') NOT NULL DEFAULT 'no'
                )";
                MySQL::query($sql,__FILE__,__LINE__);             
        }
}
?>
