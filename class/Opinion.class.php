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
class Opinion extends MySQLTableOpinion {

        /**
        * @var string Nazev tabulky v databazi.
        */
        const TABLE_NAME = "opinion";

        /**
        * @var mixed Nazvy poli tabulku v databazi, ktera se vzdy musi vyplnit. 
        */
        public static $importantColumns = array("user","book","rating");

        /**
		 * Zmeni nazor na knihu (pripadne udaje o knize).
         * @param int ID nazoru
         * @param mixed Zmeny.
         */
        public static function change($opinionID,$changes) {
        	$owner = Page::session("login");
        	$op = self::getInfo($opinionID);
        	if (($owner->id) && ($owner->id == $op->userID)) {
        		if (Book::isOnlyMine($op->bookID)) {
        			Book::change($op->bookID,$changes["bookTitle"],$changes["writerNameSecond"]." ".$changes["writerNameFirst"]);
        		}
				$opChanges = array("content" => $changes["content"], "rating" => $changes["rating"]);
				parent::change($opinionID,$opChanges);
        	}
        }
        
        /**
        * Vytvori nazor v databazi.
        * @param string Nazev knihy.
        * @param string Krestni jmeno autora.
        * @param string Prijmeni autora.
        * @param string Slovni ohodnoceni.
        * @param int Ciselne ohodnoceni.
        * @param string Klicova slova (oddelena carkou).
        * @return void
        */
        public static function create($bookTitle,$writerNameFirst,$writerNameSecond,$opinionText,$rating,$tag) {
                $writerName = "$writerNameSecond $writerNameFirst";
                try {
                        if (!$bookTitle) throw new Error(Lng::WITHOUT_BOOK_TITLE);
                        if (!$writerNameFirst or !$writerNameSecond) throw new Error(Lng::WITHOUT_WRITER_NAME);
                        if (!$tag) throw new error(Lng::WITHOUT_TAG);
                        if ($opinionText == Lng::OPINION) $opinionText = "";
                        $book = Book::create($bookTitle,$writerName);
                        TagReference::create($tag,$book->id);
                        $owner = Page::session("login");
                        $sql = "SELECT id FROM ".self::getTableName()." WHERE user = $owner->id AND book = $book->id ";
                        $res = MySQL::query($sql,__FILE__,__LINE__);
                        $op = mysql_fetch_object($res);
                        if ($op->id) {
                                throw new Error(Lng::OPINION_EXISTS);
                        }
                        $input = array(
                                "user" => $owner->id,
                                "book" => $book->id,
                                "content" => $opinionText,
                                "rating" => $rating,
                        		"date" => "now()"
                        );
                        //podivat se jestli je kniha v toReadListu a pokud ano, pak smazat
						if($toread = ReadList::isToRead($book->id)){
							ReadList::destroy($book->id);
						}
                        parent::create($input);
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
                        ".self::getTableName().".user AS userID,
                        ".self::getTableName().".content,
                        ".self::getTableName().".rating,
                        ".self::getTableName().".date,
                        ".Book::getTableName().".id AS bookID,
                        ".Book::getTableName().".title AS bookTitle,
                        ".Writer::getTableName().".id AS writerID,
                        ".Writer::getTableName().".name AS writerName,
                        ".User::getTableName().".name AS userName
                ";
        }

        /**
        * Vrati bezne pripojovani tabulek v SQL dotazech.
        * @return string
        */
        public static function getCommonJoins() {
                return "
                        INNER JOIN ".User::getTableName()." ON ".self::getTableName().".user = ".User::getTableName().".id
                        INNER JOIN ".Book::getTableName()." ON ".self::getTableName().".book = ".Book::getTableName().".id
                        INNER JOIN ".Writer::getTableName()." ON ".Book::getTableName().".writer = ".Writer::getTableName().".id
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
                        content TEXT,
                        rating ENUM ('1','2','3','4','5') NOT NULL,
                        date DATETIME default '0000-00-00 00:00:00',
                        FOREIGN KEY (user) REFERENCES ".User::getTableName()." (id),
                        FOREIGN KEY (book) REFERENCES ".Book::getTableName()." (id)
                )";
                MySQL::query($sql,__FILE__,__LINE__);
        }

        /**
        * Zjisti, zda ma prihlaseny uzivatel knihu ve svem ctenarskem deniku (v tom pripade vrati ID nazoru)
        * @param int ID knihy
        * @return boolean
        */
        public static function isMine($bookID) {
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
