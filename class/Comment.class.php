<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/

/**
* Tato trida je urcena pro praci s tabulkou, kde jsou ulozeny komentare ke kniham.
* @package reader
*/
class Comment extends MySQLTableComment {

        /**
        * @var int Pocet zobrazovanych komentaru.
        */
        const LIST_LIMIT = 1000;
        
        /**
        * @var string Nazev tabulky
        */
        const TABLE_NAME = "comment";

        /**
        * @var array_string Nazvy poli tabulku v databazi, ktera se vzdy musi vyplnit.
        */
        public static $importantColumns = array("book","user","text","date");

      
        
        /**
        * Vytvori tabulku v databazi.
        * @return void
        */ 
        public static function install() {
                $sql = "CREATE TABLE ".self::getTableName()." (
                        id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
                        book INT(25) NOT NULL,
                        user INT(25) NOT NULL,   
                        text TEXT NOT NULL,
                        date DATETIME default '0000-00-00 00:00:00',
                        rating INT(25) default 0,
                        FOREIGN KEY (book) REFERENCES ".Book::getTableName()." (id),
                        FOREIGN KEY (user) REFERENCES ".User::getTableName()." (id)
                )";
                mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
        }
        /**
        * Vytvori komentar.
        * @param int Book ID.
        * @param string Content of comment.
        * @return void
        */
        public static function create($book,$text) {
                try { 
                        $login = Page::session("login");
                        if (empty($login->id)) throw new Error(Lng::ACCESS_DENIED);
                        if (empty($text)) throw new Error(Lng::commentWithoutText);
                        $input = array(
                                "book" => $book,
                                "user" => $login->id,
                                "text" => $text,
                                "date" => "now()"
                        );
                        unset($login);
                        parent::create($input);
                }
                catch (Error $exception) {
                        $exception->scream();
                }    
        }

 	/**
 	 * Odstrani komentar
 	 * @param int ID komentare
 	 * @return void
 	 */
 	public static function destroy($id) {
 	 	$owner = Page::session("login");
 		$com = self::getInfo($id);
		try {
	 		if (($owner->level <= User::LEVEL_COMMON) && ($com->userID != $owner->id)) {
	 			throw new Error(Lng::ACCESS_DENIED);
	 		}
	 		parent::destroy($id);
	 		return TRUE;
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
                return    self::getTableName().".id,
                                ".self::getTableName().".text,
                                ".self::getTableName().".date,
                                ".self::getTableName().".rating,
                                ".User::getTableName().".id AS userID,
                                ".User::getTableName().".name AS userName,
                                ".User::getTableName().".email AS userEmail,
                                ".Book::getTableName().".id AS bookID,
                                ".Book::getTableName().".title AS bookTitle";
        }

        /**
        * Vrati bezne pripojovani tabulek v SQL dotazech.
        * @return string
        */
        protected static function getCommonJoins() {
                return "
                        INNER JOIN ".User::getTableName()." ON ".self::getTableName().".user = ".User::getTableName().".id
                        INNER JOIN ".Book::getTableName()." ON ".self::getTableName().".book = ".Book::getTableName().".id
                ";
        }

        /**
        * Vrati n naposledy komentovanych knih.
        * @param int Pocet vracenych knih.
        * @return mysql_result
        */      
        public static function lastCommentedBooks($n) {
			$sql = "
				SELECT
					COUNT(DISTINCT(".self::getTableName().".id)) AS comNumber,
					".Book::getTableName().".id AS bookID,
					".Book::getTableName().".title AS bookTitle,
					MAX(".self::getTableName().".date) AS comDate
				FROM ".self::getTableName()."
				LEFT JOIN ".Book::getTableName()." ON ".self::getTableName().".book = ".Book::getTableName().".id
				GROUP BY bookID
				ORDER BY comDate DESC
				LIMIT 0,$n
			";
			return MySQL::query($sql,__FILE__,__LINE__);
        }
        
        /**
        * Vrati komentare k dane knize
        * @param int Book ID.
        * @return mysql_result
        */
        public static function read($bookID) {
                $cond = array(0 => Book::getTableName().".id = $bookID");
              	
                return self::makeCommonQuery($cond,self::getTableName().".date",0,self::LIST_LIMIT);
        }
}
?>
