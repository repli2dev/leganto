<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/
/**
* Trida, ktera pracuje s tabulkou urcenou pro ukladani nazoru na knihy.
* @package reader
*/
class Readlist extends MySQLTableReadlist {

        /**
        * @var string Nazev tabulky v databazi.
        */
        const TABLE_NAME = "opinion";

        /**
        * @var mixed Nazvy poli tabulku v databazi, ktera se vzdy musi vyplnit. 
        */
        public static $importantColumns = array("user","book","rating");
        
        /**
        * Vytvori zaznam v databazi.

        * 
        * @param string ID knihy
        * @return void
        */
        public static function create($bookID) {
                try {
               
                        if (!$bookID) throw new Error(Lng::WITHOUT_BOOK_ID);
                        $owner = Page::session("login");
                        $sql = "SELECT id FROM ".self::getTableName()." WHERE user = $owner->id AND book = $bookID ";
                        $res = MySQL::query($sql,__FILE__,__LINE__);
                        $op = mysql_fetch_object($res);
                        if ($op->id) {
                                throw new Error(Lng::ALREADY_IN_READLIST);
                        }
                        $input = array(
                                "user" => $owner->id,
                                "book" => $bookID,
                        );
                        parent::create($input);
                }
                catch(Error $e) {
                        $e->scream();
                }
        }
        
        /**
        * Smaze zaznam v databazi.

        * 
        * @param string ID knihy
        * @return void
        */
        public static function destroy($bookID) {
                try {
               
                        if (!$bookID) throw new Error(Lng::WITHOUT_BOOK_ID);
                        $owner = Page::session("login");
                        $sql = "SELECT id FROM ".self::getTableName()." WHERE user = $owner->id AND book = $bookID ";
                        $res = MySQL::query($sql,__FILE__,__LINE__);
                        $op = mysql_fetch_object($res);
                        if (!$op->id) {
                                throw new Error(Lng::NOT_IN_READLIST);
                        }
                        $cond = array(
							0 => "book = '".Page::get("book")."' AND user = '".$owner->id."'"
						);
                        parent::destroyByCond($cond);
                }
                catch(Error $e) {
                        $e->scream();
                }
        }

        /**
        * Vrati bezne pozadovane polozky pro SQL dotaz.
        * @return string
        */
        public static function getCommonItems() {
                return "
                        ".self::getTableName().".id,
                        ".self::getTableName().".user AS userID
                ";
        }

        /**
        * Vrati bezne pripojovani tabulek v SQL dotazech.
        * @return string
        */
        public static function getCommonJoins() {
                return "
                ";
        }
        
        /**
        * Vrati nazory na danou knihu.
        * @param int ID knihy
        * @return mysql_result
        */
        public static function getListByBook($book) {
                $cond = array(0 => Book::getTableName().".id = $book");
                return self::makeCommonQuery($cond,User::getTableName().".name",0,1000);
        } 
        
        /**
        * Vytvori tabulku v databazi.
        * @return void
        */
        public static function install() {
                $sql = "CREATE TABLE ".self::getTableName()." (
                        id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
                        user INT(25) NOT NULL,
                        book INT(25) NOT NULL,
                )";
                MySQL::query($sql,__FILE__,__LINE__);
        }

        /**
        * Zjisti, zda ma prihlaseny uzivatel knihu ve svem ctenarskem deniku (v tom pripade vrati ID nazoru)
        * @param int ID knihy
        * @return boolean
        */
        public static function isToRead($bookID) {
                $owner = Page::session("login");
                $sql = "SELECT id FROM ".self::getTableName()." WHERE book = $bookID AND user = ".$owner->id;
                $res = MySQL::query($sql,__FILE__,__LINE__);
                if (mysql_num_rows($res) > 0) {
                	$result = mysql_fetch_object($res);
                	return $result->id;
                }
                else return FALSE;
        }

}
