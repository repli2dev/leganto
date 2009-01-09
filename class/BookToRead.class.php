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
class BookToRead extends MySQLTableReadlist {
        
        /**
        * @var string Nazev tabulky.
        */
        const TABLE_NAME = "book";

        /**
        * @var int Pocet vracenych polozek, je-li vracen seznam.
        */
        const LIST_LIMIT = 50;

        /**
        * @var int Pocet vracenych polozek u mensich seznamu.
        */
        const SMALL_LIST_LIMIT = 10;

        /**
        * Vrati bezne pozadovane polozky pro SQL dotaz.
        * @return string
        */
        public static function getCommonItems() {
                return "
                        ".Readlist::getTableName().".book AS id,
						".Readlist::getTableName().".user,
						".Book::getTableName().".title,
						".Writer::getTableName().".name AS writerName,
						".Writer::getTableName().".id AS writerId,
						ROUND(AVG(".Opinion::getTableName().".rating)) AS rating
                ";
        }

        /**
        * Vrati bezne pripojovani tabulek v SQL dotazech.
        * @return string
        */
        public static function getCommonJoins() {
                return "
                        LEFT JOIN ".Book::getTableName()." ON ".Readlist::getTableName().".book = ".Book::getTableName().".id
						LEFT JOIN ".Writer::getTableName()." ON ".Book::getTableName().".writer = ".Writer::getTableName().".id
						LEFT JOIN ".Opinion::getTableName()." ON ".Readlist::getTableName().".book = ".Opinion::getTableName().".book
                ";
        }

        /**
        * Vrati knihy uzivatele.
        * @param int ID uzivatele
        * @param string Nazev polozky, podle ktere se maji knihy seradit.
        * @param int Cislo stranky.
        * @return book_mysql_query
        */
        public static function byUser($usID,$order,$page) {
        	if ($order == "") {
        		$order = "rating DESC";
        	}
        	$sql = "
					SELECT
					".self::getCommonItems()."
					FROM ".Readlist::getTableName()."
					".self::getCommonJoins()."
					WHERE ".Readlist::getTableName().".user = ".$usID."
					GROUP BY ".Readlist::getTableName().".id
					ORDER BY ".$order."
					LIMIT ".$page*self::LIST_LIMIT.",".self::LIST_LIMIT."
                "; 
        	$res = MySQL::query($sql,__FILE__,__LINE__);
        	return $res;
        }
        /**
         * Instalace tabulky - definována kvůli abstraktnímu předku
         *
         */
        public static function install(){
        	
        }
}
?>
