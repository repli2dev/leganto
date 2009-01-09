<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/
/**
* Trida, ktera pracuje s tabulkou urcenou pro ukladani informaci o tom, zda dany uzivatel
* chce vyhledavat v dane knihovne.
* @package reader
*/
class LibraryMenu extends MySQLTableLibraryMenu {
        
        /**
        * @var string Nazev tabulky.
        */
        const TABLE_NAME = "libraryMenu";

        /**
        * @var mixed Nazvy poli tabulku v databazi, ktera se vzdy musi vyplnit. 
        */
        public static $importantColumns = array("library","user");

        /**
         * Vytvori polozku v databazi.
         * 
         * @param mixed Vstup ve formatu array("libraryID" => "yes/no").
         * @return boolean
         */
        public static function create($input) {
        	try {
        		$owner = Page::session("login");
        		if ($owner->level < User::LEVEL_COMMON) {
        			throw new Error(Lng::ACCESS_DENIED);
        		}
        		foreach($input AS $key => $value) {
        			self::destroyByCond(array(
        				0 => "library = $key",
        				"AND" => "user = $owner->id"
        			));
        			if ($value == "yes" && ($key != 0)) {
        				$in = array(
        					"library" => $key,
        					"user" => $owner->id
        				);
       					parent::create($in);
        			}
        		}
        		return TRUE;
        	}
        	catch (Error $e) {
        		$e->scream();
        	}
        }
        
        /**
         * Vrati informace o tom, ve kterych knihovnach dany uzivatel vyhledava,
         * ve formatu array(ID knihovny => "yes/no");
         * 
         * @param int ID uzivatele
         * @return mixed
         */
        public static function getByUser() {
        	
        }
        
        /**
        * Vrati bezne pozadovane polozky pro SQL dotaz.
        * @return string
        */
        protected static function getCommonItems() {
                return "*";
        }

        /**
        * Vrati bezne pripojovani tabulek v SQL dotazech.
        * @return string
        */
        protected static function getCommonJoins() {
                return "";
        }
  
        /**
        * Vytvori tabulku v databazi.
        * @return void
        */
        public static function install() {
                $sql = "CREATE TABLE ".self::getTableName()." (
                        id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
                        user INT(25) NOT NULL,
                        library INT(25) NOT NULL,
                        FOREIGN KEY (user) REFERENCES ".User::getTableName()." (id),
                        FOREIGN KEY (library) REFERENCES ".Library::getTableName()." (id)
                )";
                MySQL::query($sql,__FILE__,__LINE__);             
        }
}
?>
