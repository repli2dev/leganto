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
class Book extends MySQLTableBook {
        
        /**
        * @var string Nazev tabulky.
        */
        const TABLE_NAME = "book";

        /**
        * @var int Pocet vracenych polozek, je-li vracen seznam.
        */
        const LIST_LIMIT = 50;

        /**
        * @var mixed Nazvy poli tabulku v databazi, ktera se vzdy musi vyplnit. 
        */
        public static $importantColumns = array("title","writer");

        /**
        * @var int Pocet vracenych polozek u mensich seznamu.
        */
        const SMALL_LIST_LIMIT = 10;

        /**
        * Vrati bezne pozadovane polozky pro SQL dotaz.
        * @return string
        */
        protected static function getCommonItems() {
                return "
                        ".self::getTableName().".id,
                        ".self::getTableName().".title AS title,
                        ".self::getTableName().".asciiTitle,
                        ".self::getTableName().".date AS date,
                        ".Writer::getTableName().".id AS writerID,
                        ".Writer::getTableName().".name AS writerName,
                        COUNT(".Comment::getTableName().".id) AS commentCount,
                        ROUND(AVG(".Opinion::getTableName().".rating)) AS rating,
                        COUNT(".Opinion::getTableName().".id) AS countRead
                ";
        }

        /**
        * Vrati bezne pripojovani tabulek v SQL dotazech.
        * @return string
        */
        protected static function getCommonJoins() {
                return "
                        LEFT JOIN ".Writer::getTableName()." ON ".self::getTableName().".writer = ".Writer::getTableName().".id
                        LEFT JOIN ".Comment::getTableName()." ON ".self::getTableName().".id = ".Comment::getTableName().".book
                        LEFT JOIN ".Opinion::getTableName()." ON ".self::getTableName().".id = ".Opinion::getTableName().".book
                ";
        }

        /**
        * Vrati knihy od oblibenych uzivatelu prihlaseneho uzivatele.
        * @return book_mysql_result
        */
        public static function byFavourite() {
        	$owner = Page::session("login");
        	$sql = "
        		SELECT
        			".self::getCommonItems().",
        			".User::getTableName().".name AS userName
        		FROM ".self::getTableName()."
        		".self::getCommonJoins()."
        		INNER JOIN ".Recommend::getTableName()." ON ".Opinion::getTableName().".user = ".Recommend::getTableName().".recommend
        		INNER JOIN ".User::getTableName()." ON ".Recommend::getTableName().".recommend = ".User::getTableName().".id
        		WHERE ".Recommend::getTableName().".user = $owner->id
        		GROUP BY ".self::getTableName().".id
        		ORDER BY ".Opinion::getTableName().".date DESC
        		LIMIT 0,5
        	";
        	return MySQL::query($sql,__FILE__,__LINE__);
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
        	$cond = array(0 => User::getTableName().".id = $usID");
        	return self::makeCommonQuery($cond,$order,$page*self::LIST_LIMIT,self::LIST_LIMIT);
        }

        /**
        * Spoji dve knihy v databazi.
        * @param int ID vychozi knihy.
        * @param int ID cilove knihy (v tuto knihu se obe dve spoji).
        * @return void
        */
        public static function connect($first,$second) {
                if ($first != $second) {
	        		$sql = "
	                	UPDATE ".TagReference::getTableName()."
	                	SET book = $second
	                	WHERE book = $first 
	            	";
	                MySQL::query($sql,__FILE__,__LINE__);
	                $sql = "
	                	UPDATE ".Opinion::getTableName()."
	                	SET book = $second
	                	WHERE book = $first
	                ";
	                MySQL::query($sql,__FILE__,__LINE__);
	                $sql = "
	                	UPDATE ".Comment::getTableName()."
	                	SET book = $second
	                	WHERE book = $first
	                ";                
	                MySQL::query($sql,__FILE__,__LINE__);
	                Wiki::destroyByCond(array(0 => "book = $first"));
	                parent::destroy($first);
                }
        }

        /**
        * Vytvori knihu v databazi.
        * @param string Nazev knihy.
        * @param string Jmeno autora.
        * @return record
        */
        public static function create($bookTitle,$writerName) {
                $asciiTitle = String::utf2lowerAscii($bookTitle);
                $asciiName = String::utf2lowerAscii($writerName);
                $cond = array(
                        0 => self::getTableName().".asciiTitle = '$asciiTitle'",
                        "AND" => Writer::getTableName().".asciiName = '$asciiName'"
                );
                $book = self::getInfoByCond($cond);
                if (!$book->id) {
                        $writerID = Writer::Create($writerName);
                        $input = array(
                                "title" => $bookTitle,
                                "asciiTitle" => $asciiTitle,
                                "writer" => $writerID,
                        		"date" => "now()"
                        );
                        return parent::create($input);
                }
                else {
                        return self::getInfo($book->id);
                }
        }

        /**
         * Zmeni informace o knize.
         * @param int ID knihy.
         * @param string Nazev knihy.
         * @param string Jmeno autora.
         * @return void
         */
        public static function change($id,$title,$writer) {
			$bookNew = self::getInfoByItem("asciiTitle",String::utf2lowerAscii($title));
			$duplicity = Writer::getDuplicity($writer);
			if (($bookNew->writerName == $duplicity->name) && (isset($bookNew->writerName))) {
				self::connect($id,$bookNew->id);
			}
			else {
        		$change = array("title" => $title, "asciiTitle" => String::utf2lowerAscii($title));
        		parent::change($id,$change);
        		unset($change);
        		$bookOld = self::getInfo($id);
        		Writer::change($bookOld->writerID,$writer);
        		unset($bookOld);
			}
			unset($bookNew);
        }
        
        /**
        * Vrati nazvy vsech knih v databazi serazene podle abecedy.
        * @return mixed
        */
        public static function getAll() {
                $sql = "
                        SELECT
                                DISTINCT title
                        FROM ".self::getTableName()."
                        ORDER BY title
                ";
                $res = MySQL::query($sql,__FILE__,__LINE__);
                $result = array();
                while($book = mysql_fetch_object($res)) {
                	$result[] = $book->title;
                }
                return $result;
        }

		/**
		 * Vrati JSON ve formatu {'bookTitle','writeNameFirst'}
		 * @return string
		 */
        public static function getJSON() {
        	$sql = "
        		SELECT
        			".self::getTableName().".title AS bookTitle,
        			".Writer::getTableName().".name AS writerName
        		FROM
        			".self::getTableName()."
        		INNER JOIN ".Writer::getTableName()." ON ".self::getTableName().".writer = ".Writer::getTableName().".id
        		GROUP BY ".self::getTableName().".id
        		ORDER BY ".self::getTableName().".title
        	";
        	$res = MySQL::query($sql,__FILE__,__LINE__);
        	while ($item = mysql_fetch_array($res)) {
        		$json[] = $item;
        	}
        	return (json_encode($json));
        	
        }
        
        /**     
        * Vrati knihy na zaklade podminky.
        * @param mixed Pole podminek
        * @param string Polozka, podle ktere ma byt vystup serazen.
        * @param int Cislo zobrazovane stranky (zacina se od 0).
        * @param int Pocet vracenych knih.
        */
        private static function getList($cond,$order,$page,$limit) {
                $limit1 = $page*$limit;
                return self::makeCommonQuery($cond,$order,$limit1,$limit);
        }

        /**
        * Vrati podobne knihy k dane knize (omezeny pocet).
        * @param int ID knihy.
        * @return book_mysql_result 
        */
        public static function getSimilar($bookID) {
        	$sql = "
        		SELECT
        			".self::getCommonItems()."
        		FROM ".self::getTableName()."
        		".self::getCommonJoins()."
        		LEFT JOIN ".BookSim::getTableName()." ON ".self::getTableName().".id = ".BookSim::getTableName().".book
        		WHERE
        			".BookSim::getTableName().".bookFriend = $bookID
        		GROUP BY ".self::getTableName().".id
        		ORDER BY ".BookSim::getTableName().".similarity DESC
        		LIMIT 0,".self::SMALL_LIST_LIMIT."
        	";
        	return MySQL::query($sql,__FILE__,__LINE__);
        }

        /**
        * Vytvori tabulku v databazi.
        * @return void
        */
        public static function install() {
                $sql = "CREATE TABLE ".self::getTableName()." (
                        id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
                        title VARCHAR(250),
                        asciiTitle VARCHAR(250),
                        writer INT(25),
                        date DATETIME default '0000-00-00 00:00:00',
                        FOREIGN KEY (writer) REFERENCES ".self::getTableName()." (id)
                )";
                MySQL::query($sql,__FILE__,__LINE__);             
        }

        /**
         * Zjisti, zda ma knihu pridanu ve svem ctenarskem deniku pouze jeden (prihlaseny) uzivatel.
         * @param int ID knihy.
         * @return boolean
         */
        public static function isOnlyMine($bookID) {
        	$book = self::getInfo($bookID);
        	$op = mysql_fetch_object(Opinion::getListByBook($bookID));
        	$owner = Page::session("login");
        	if (($book->countRead == 1) && ($op->userID == $owner->id)) {
        		return TRUE;
        	}
        	else {
        		return FALSE;
        	}
        }
        
        /**
        * Vrati naposledy pridane knihy. Pokud je zadano ID uzivatele, vrati naposledy pridane knihy daneho uzivatele.
        * @see Book:SMALL_LIST_LIMIT
        * @param int ID uzivatele
        * @return book_mysql_result
        */
        public static function last($user = NULL) {
        	$cond = array();
        	if ($user) {
        		$cond[] = Opinion::getTableName().".user = $user";
        		$order = Opinion::getTableName().".date DESC";
        	}
        	else {
        		$order = "date DESC";
        	}
        	return self::makeCommonQuery($cond,Opinion::getTableName().".date DESC",0,self::SMALL_LIST_LIMIT);
        }

        /**
        * Vrati nejlepe hodnocene knihy. Pokud je zadano ID uzivatele, vrati nejlepe hodnocene knihy daneho uzivatele.
        * @see Book:SMALL_LIST_LIMIT
        * @param int ID uzivatele
        * @return book_mysql_result
        */
        public static function top($user = NULL) {
        	$cond = array();
        	if ($user) {
        		$cond[] = Opinion::getTableName().".user = $user";
        	}
        	return self::makeCommonQuery($cond,"rating DESC, countRead DESC",0,self::SMALL_LIST_LIMIT);
        }
}
?>
