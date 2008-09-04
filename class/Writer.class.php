<?php


/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/
/**
* Trida, ktera pracuje s tabulkou urcenou pro ukladani knih.
* @package reader
*/
class Writer extends MySQLTableWriter {
        
        /**
        * @var string Nazev tabulky.
        */
        const TABLE_NAME = "writer";

        /**
        * @var mixed Nazvy poli tabulku v databazi, ktera se vzdy musi vyplnit. 
        */
        public static $importantColumns = array("name","asciiName");

        /**
        * @var int Pocet vracenych polozek, je-li vracen seznam.
        */
        const LIST_LIMIT = 50;

        /**
         * Zmeni jemno autora.
         * @param int ID autora.
		 * @param string Nove jmeno autora.
		 * @return void
         */
        public static function change($id,$name) {
        	if ($writer = self::getDuplicity($name)) {
        		if ($writer->id != $id) {
        			self::connect($id,$writer->id);
        		}
        	}
        	else {
        		parent::change($id,array("name" => $name,"asciiName" => String::utf2lowerAscii($name)));
        	}
        }
        
        /**
        * Spoji dva autory do jednoho.
        * @param int ID prvniho autora (tento zanikne).
        * @param int ID druheho autora (tento zustane).
        * @return void
        */
        public static function connect($start,$finish) {
        	if ($start != $finish) {
                $sql = "UPDATE ".Book::getTableName()." SET writer = $finish WHERE writer = $start";
                mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
                self::destroy($start);
        	}
        }

        /**
        * Vytvori autora v databazi.
        * @param string Jmeno autora (nejprve prijmeni, pote krestni).
        * @return int ID autora.
        */
        public static function create($name) {
                $writer = self::getDuplicity($name);
                if (!$writer->id) {
                        $asciiName = String::utf2lowerAscii($name);
                        parent::create(array("name" => $name, "asciiName" => $asciiName));
                        $writerID = mysql_insert_id();
                        return $writerID;
                }
                else {
                	return $writer->id;
                }
        }

        /**
        * Vrati autora, ktery by mohl byt s danym autorem duplicitni.
        * @param string Jmeno autora.
        * @return record autor (id,name)
        */
        public static function getDuplicity($writer) {
                $writer = String::utf2lowerAscii($writer);
                $writerList = explode(" ",$writer);
                $condition = "WHERE ";
                $help = false;
                foreach($writerList as $writerItem) {
                        if ($help) $condition .= " AND ";
                                $condition .= "asciiName LIKE '%$writerItem%'";
                                $help = true;
                        }
                $sql = "SELECT id,name FROM ".self::getTableName()." $condition";
                $res = MySQL::query($sql,__FILE__,__LINE__);
                $record = mysql_fetch_object($res);
                return $record;
        } 

        /**
        * Vrati jmena vsech autoru.
        * @return mysql_result
        */
        public static function getAll() {
                $sql = "SELECT name FROM ".self::getTableName();
                return MySQL::query($sql,__FILE__,__LINE__);
        }

        /**
        * Vrati krestni jmeno z daneho jmena autora.
        * @param string Jmeno autora.
        * @return string
        */
        public static function getNameFirst($name) {
                $name = explode(" ",$name);
                $help = 0;
                $first = "";
                foreach ($name AS $item) {
                        if ($help) {
                                if ($help > 1) {
                                        $first .= " ";
                                }
                                $help++;
                                $first .= $item;
                        } 
                        else {
                                $help = 1;
                        }
                }
                return $first;
        } 

        /**
         * Vrati pole krestnich jmen.
         * @return mixed
         */
        public static function getNameListFirst() {
        	$res = self::getAll();
        	$result = array();
        	$help = array();
        	while ($name = mysql_fetch_object($res)) {
        		$name = self::getNameFirst($name->name);
        		if (!$help[$name]) {
        			$help[$name] = TRUE;
        			$result[] = $name;
        		}
        	}
        	unset($help);
        	return $result;
        }
        
        /**
         * Vrati pole prijmeni.
         * @return mixed
         */
        public static function getNameListSecond() {
        	$res = self::getAll();
        	$result = array();
        	$help = array();
        	while ($name = mysql_fetch_object($res)) {
        		$name = self::getNameSecond($name->name);
        		if (!$help[$name]) {
        			$help[$name] = TRUE;
        			$result[] = $name;
        		}
        	}
        	unset($help);
        	return $result;
        }        
        
        /**
        * Vrati prijmeni z daneho jmena autora.
        * @param string Jmeno autora.
        * @return string
        */
        public static function getNameSecond($name) {
                $second = explode(" ",$name);
                return $second[0]; 
        }

        /**
        * Vytvori tabulku v databazi.
        * @return void
        */
        public static function install() {
                $sql = "CREATE TABLE ".self::getTableName()." (
                        id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
                        name VARCHAR(250) NOT NULL,
                        asciiName VARCHAR(250) NOT NULL
                )";
                MySQL::query($sql,__FILE__,__LINE__);
        }

        /**
        * Vrati seznam vyhledanych autoru.
        * @param string Hledane jmeno.
        * @param string Polozka, podle ktere se seznam seradi.
        * @param int Cislo zobrazovane stranky.
        * @return mysql_result
        */
        public static function search($name,$order,$page) {
                switch($order) {
                        default: $order = "ORDER BY $order"; break;
                        case "": $order = "ORDER BY name";
                }
                $limit = $page*(self::LIST_LIMIT);
                $name = String::cut(String::utf2lowerAscii($name));
                foreach($name AS $item) {
                        if ($condition) {
                                $condition .= " AND ";
                        }
                        $condition .= self::getTableName().".asciiName LIKE '%$item%'";
                }
                $sql = "
                        SELECT
                                ".self::getTableName().".id,
                                ".self::getTableName().".name,
                                COUNT(".Book::getTableName().".id) countBook,
                                (SELECT COUNT(id) FROM ".self::getTableName()." WHERE $condition) AS pageHelp
                        FROM
                                ".self::getTableName()."
                        LEFT JOIN ".Book::getTableName()." ON ".self::getTableName().".id = ".Book::getTableName().".writer
                        WHERE $condition
                        GROUP BY ".self::getTableName().".id
                        $order
                        LIMIT $limit,".self::LIST_LIMIT."
                ";
                return MySQL::query($sql,__FILE__,__LINE__);
        }

}
