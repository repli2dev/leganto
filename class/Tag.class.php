<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/
/**
* Trida, ktera pracuje s tabulkou urcenou pro ukladani klicovych slov.
* @package reader
*/
class Tag extends MySQLTableTag {
        
        /**
        * @var string Nazev tabulky.
        */
        const TABLE_NAME = "tag";

        /**
        * @var mixed Nazvy poli tabulku v databazi, ktera se vzdy musi vyplnit. 
        */
        private static $importantColumns = array("name","asciiName");

        /**
        * @var int Pocet vracenych polozek, je-li vracen seznam.
        */
        const LIST_LIMIT = 50;

        /**
         * Zmeni klicove slovo.
         * @param int ID klicoveho slova.
         * @param string Nove zneni klicoveho slova.
         * @return void
         */
        public static function change($id,$tag) {
        	$help = self::getInfoByItem("asciiName",String::utf2lowerAscii($tag));
        	if ($help->id) {
        		TagReference::connect($id,$help->id);
        	}
        	else {
        		parent::change($id,array("name" => $tag,"asciiName" => String::utf2lowerAscii($tag)));
        	}
        	unset($help);
        }
        
        /**
        * Vytvori klicove slovo v databazi.
        * @param string Klicove slovo.
        * @retun int ID vlozeneho klicoveho slova.
        */
        public static function create($name) {
                $id = self::getID($name);
                $asciiName = String::utf2lowerAscii($name);
                if (!$id) {
                        parent::create(array("name" => $name,"asciiName" => $asciiName));
                        return mysql_insert_id();
                }
                else {
                        return $id;
                }
        }

        /**
        * Vrati klicova slova k dane knize.
        * @param int ID knihy.
        * @return mysql_result
        */
        public function getByBook($bookID) {
                $sql = "
                        SELECT
                                ".self::getCommonItems()."
                        FROM ".TagReference::getTableName()."
                        LEFT JOIN ".self::getTableName()." ON ".TagReference::getTableName().".tag = ".self::getTableName().".id
                        WHERE ".TagReference::getTableName().".book = $bookID
                        GROUP BY ".self::getTableName().".id
                        ORDER BY ".self::getTableName().".name
                ";
                return MySQL::query($sql,__FILE__,__LINE__);
        }

        /**
        * Vrati bezne pozadovane polozky pro SQL dotaz.
        * @return string
        */
        protected static function getCommonItems() {
                return "
                        ".self::getTableName().".id,
                        ".self::getTableName().".name,
                        ROUND((
                                (SELECT COUNT(id) AS thisCount FROM ".TagReference::getTableName()." WHERE tag = ".self::getTableName().".id)/
                                (SELECT COUNT(id) AS tagCount FROM ".TagReference::getTableName().")
                        )*100) AS size
                ";
        }

        /**
        * Vrati ID daneho klicoveho slova.
        * @param string Klicove slovo
        * @return int
        */
        public function getID($name) {
                $asciiName = String::utf2lowerAscii($name);
                $sql = "SELECT id FROM ".self::getTableName()." WHERE asciiName = '$asciiName'";
                $record = mysql_fetch_object(MySQL::query($sql,__FILE__,__LINE__));
                return $record->id;
        }

        /**
        * Vrati nejpouzivanejsi klicova slova.
        * @return mysql_result
        */
        public function getListTop() { 
                $sql = "
                        SELECT ".self::getTableName().".id 
                        FROM ".self::getTableName()."
                        ORDER BY
                        (SELECT COUNT(id) AS thisCount FROM ".TagReference::getTableName()." WHERE tag = ".self::getTableName().".id)
                        DESC
                        LIMIT 0,".self::LIST_LIMIT."
                ";
                $res = MySQL::query($sql,__FILE__,__LINE__);
                $help = "";
                while ($record = mysql_fetch_object($res)) {
                        if ($help != "") {
                                $help .= ", ";
                        }
                        $help .= $record->id;
                }
                if ($help) {
	                $sql = "
	                        SELECT
	                                ".self::getCommonItems()."
	                        FROM ".self::getTableName()."
	                        WHERE
	                        ".self::getTableName().".id IN ($help) 
	                        GROUP BY ".self::getTableName().".id
	                        ORDER BY ".self::getTableName().".name
	                ";
	                return MySQL::query($sql,__FILE__,__LINE__);
                }
        } 

        /**
        * Vrati klicove slovo na zaklade jeho ID.
        * @param int ID klicoveho slova.
        * @return string Klicove slovo.
        */
        public function getName($id) {
                $sql = "SELECT name FROM ".self::getTableName." WHERE id = $id";
                $record = mysql_fetch_object(MySQL::query($sql,__FILE__,__LINE__));
                return $record->name;
        }

        /**
        * Vytvori tabulku v databazi.
        * @return void
        */
        public static function install() {
                $sql = "CREATE TABLE ".self::getTableName()." (
                        id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
                        name VARCHAR(64) UNIQUE NOT NULL,
                        asciiName VARCHAR(64) NOT NULL
                )";
                MySQL::query($sql,__FILE__,__LINE__);
        }

        /**
        * Vrati seznam vyhledanych klicovych slov.
        * @param string Klicobe slovo.
        * @param string Polozka, podle ktere bude seznam serazen.
        * @param int Cislo zobrazovane stranky.
        */
        public function search($tag,$order,$page) {
				if (empty($order)) {
					$order = "ORDER BY name";
				}
				else {
					$order = "ORDER BY ".$order;
				}
                $limit = $page*(self::LIST_LIMIT);
                $tag = String::cut(String::utf2lowerAscii($tag));
                $condition = "";
                foreach ($tag as $item) {
                        if ($condition) {
                                $condition .= " OR ";
                        }
                        $condition .= self::getTableName().".asciiName LIKE '%$item%'";
                }
                $sql = "
                        SELECT
                                ".self::getCommonItems().",
                                COUNT(".TagReference::getTableName().".id) AS tagged
                        FROM ".self::getTableName()."
                        LEFT JOIN ".TagReference::getTableName()." ON ".self::getTableName().".id = ".TagReference::getTableName().".tag
                        WHERE $condition
                        GROUP BY ".self::getTableName().".id
                        $order
                        LIMIT $limit,".self::LIST_LIMIT
                ;
                return MySQL::query($sql,__FILE__,__LINE__);
        }

}
?>
