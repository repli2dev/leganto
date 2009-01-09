<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Trida slouzici pro vyhledavani
* @example doc_example/MySQLTable.phps
*/
class Search {

	/**
	* @var int Pocet zobrazovanych polozek.
	*/
 	const LIST_LIMIT = 100;	
	
	/**
	 * 			Vytvori podminku pro hledani podle vsech kriterii u knih.
	 * @param	mixed	Hledana fraze.
	 * @return	string
	 */
	private static function bookCondByAll($value) {
		$condition = "(".self::bookCondByBook($value).") OR (".self::bookCondByWriter($value).") OR (".self::bookCondByTag($value).")";
		return $condition; 
	}

	/**
	 * 			Vytvori podminku pro hledani podle vsech kriterii u soutezi,
	 * @param 	mixed	Hledana fraze.
	 * @return 	string
	 */
	private static function compCondByAll($value) {
		$condition= "(".self::competitionCondByTag($value).")";
		return $condition;
	}
	
	/**
	 * 			Vytvori podminku pro vyhledavani v knihach.
	 * @param	mixed	Hledana fraze.
	 * @return	string
	 */
	private static function bookCondByBook($value) {
		$condition = "";
		foreach($value AS $item) {
			if ($condition) {
				$condition .= " AND ";
			}
			$condition .= Book::getTableName().".asciiTitle LIKE '%$item%'";  
		}  
		return $condition;
	} 
 
	/**
	 * 			Vytvori podminku pro hledani v klicovych slovech u knizek.
	 * @param 	mixed	Hledana fraze.
	 * @return 	string
	 */
	private static function bookCondByTag($value) {	
		foreach ($value as $item) {
			if ($condition) {
				$condition .= " AND ";
			}
			$condition .= Book::getTableName().".id IN (
				SELECT ".TagReference::getTableName().".target AS book
				FROM ".TagReference::getTableName()."
				LEFT JOIN ".Tag::getTableName()." ON ".TagReference::getTableName().".tag = ".Tag::getTableName().".id
				WHERE
					".Tag::getTableName().".asciiName LIKE '%$item%'
				AND 
					".TagReference::getTableName().".type = 'book'
			)";  
  		}
		return $condition;
	}

	/**
	 *			Vytvori podminku pro vyhledavani v autorech u knih.
	 * @param	mixed	Hledana fraze
	 * @return 	string
	 */
	private static function bookCondByWriter($value) {
		$condition = "";
		foreach($value AS $item) {
			if ($condition) {
				$condition .= " AND ";
			}

			$condition .= Writer::getTableName().".asciiName LIKE '%$item%'";  
		}
		return $condition;
	}

	/**
	 * 			Vytvori podminku pro vyhledavani klicovych slov v soutezich.
	 * @param	mixed	Hledana fraze.
	 * @return	string
	 */
	private static function competitionCondByTag($value) {
		foreach ($value as $item) {
			if ($condition) {
				$condition .= " AND ";
			}
			$condition .= Competition::getTableName().".id IN (
				SELECT ".TagReference::getTableName().".target AS comp
				FROM ".TagReference::getTableName()."
				LEFT JOIN ".Tag::getTableName()." ON ".TagReference::getTableName().".tag = ".Tag::getTableName().".id
				WHERE
					".Tag::getTableName().".asciiName LIKE '%$item%'
				AND 
					".TagReference::getTableName().".type = 'competition'
			)";
		}
		return $condition;
	}
	
	/**
	 * Vrati SQL dotaz pro vyhledavani.
	 * @param string Podminka.
	 * @param string Polozka, podle ktere bude vysledek serazen.
	 * @param int Cislo zobrazovane stranky.
	 * @return string
	 */
	private static function getBookQuery($condition,$order,$page) {
		$order = "ORDER BY $order";
		$limit = $page*(self::LIST_LIMIT);
		$sql = "
			SELECT
				".Book::getTableName().".id,
				".Book::getTableName().".title AS title,
				".Book::getTableName().".asciiTitle,
				".Book::getTableName().".date AS date,
				".Writer::getTableName().".id AS writerID,
				".Writer::getTableName().".name AS writerName,
				COUNT(".Comment::getTableName().".id) AS commentCount,
				ROUND(AVG(".Opinion::getTableName().".rating)) as rating,
				(SELECT COUNT(".Opinion::getTableName().".id) FROM ".Opinion::getTableName()." WHERE ".Opinion::getTableName().".book = ".Book::getTableName().".id) AS countRead,
				(
					SELECT COUNT(".Book::getTableName().".id)
					FROM ".Book::getTableName()."
					INNER JOIN ".Writer::getTableName()." ON ".Book::getTableName().".writer = ".Writer::getTableName().".id
					WHERE $condition
				) AS pageHelp
			FROM ".Book::getTableName()."
			INNER JOIN ".Writer::getTableName()." ON ".Book::getTableName().".writer = ".Writer::getTableName().".id
			LEFT JOIN ".Comment::getTableName()." ON ".Book::getTableName().".id = ".Comment::getTableName().".book
			INNER JOIN ".Opinion::getTableName()." ON ".Book::getTableName().".id = ".Opinion::getTableName().".book
			WHERE
			$condition
			GROUP BY ".Book::getTableName().".id
			$order
			LIMIT $limit,".self::LIST_LIMIT
		;
		return $sql;
	}
	
	/**
	 * Vrati SQL dotaz pro vyhledavani soutezi.
	 * @param string Podminka.
	 * @param string Polozka, podle ktere bude vysledek serazen.
	 * @param int Cislo zobrazovane stranky.
	 * @return string
	 */
	private static function getCompetitionQuery($condition,$order,$page) {
		return "
			SELECT
				".Competition::getTableName().".id AS id,
				".Competition::getTableName().".name AS name,
				".Competition::getTableName().".user AS userID,
				".Competition::getTableName().".expiration AS expiration,
				".Competition::getTableName().".date AS date,
				".User::getTableName().".name AS userName
			FROM ".Competition::getTableName()."
			INNER JOIN ".User::getTableName()." ON ".Competition::getTableName().".user = ".User::getTableName().".id
			WHERE
			$condition
			AND expiration > now()
			GROUP BY ".Competition::getTableName().".id
			ORDER BY $order
			LIMIT ".($page*self::LIST_LIMIT).",".self::LIST_LIMIT."
		";		
	}
	/**
	 * Vrati SQL dotaz pro vyhledavani vlastni tvorby.
	 * @param string Podminka.
	 * @param string Polozka, podle ktere bude vysledek serazen.
	 * @param int Cislo zobrazovane stranky.
	 * @return string
	 */
	private static function getWritingQuery($condition,$order,$page) {
		return "
			SELECT
				".Writing::getTableName().".id AS id,
				".Writing::getTableName().".title AS title,
				".Writing::getTableName().".date AS date,
				".User::getTableName().".id AS userID,
				".User::getTableName().".name AS userName
			FROM ".Writing::getTableName()."
			INNER JOIN ".User::getTableName()." ON ".Writing::getTableName().".user = ".User::getTableName().".id
			WHERE
			$condition
			GROUP BY ".Writing::getTableName().".id
			ORDER BY $order
			LIMIT ".($page*self::LIST_LIMIT).",".self::LIST_LIMIT."
		";		
	}
	
	/**
	 * Vrati polozky na zaklade hledane fraze.
	 * @param string Typ vyhledavani ('competition','book','writing').
	 * @param string Hledana fraze.
	 * @param string Polozka, podle ktere bude vysledek serazen ('rating','readed','').
	 * @param int Cislo zobrazovane stranky.
	 * @param boolean rika jestli chceme byt presmerovani v pripade ze je vracen prave jeden radek
	 * @return mysql_result
	 */
	public function search($type,$value,$order,$page,$redirect = TRUE) {
		$value = String::cut(String::utf2ascii($value));
		if (empty($page)) {
			$page = 0;
		}
		switch ($type) {
			case "book":
				switch($order) {
					case "":
						$order = "rating DESC,countRead DESC";
						break;
					case "rating DESC":
						$order .= ",countRead DESC";
						break;
				}
				$link = "book.php?book=";
				$sql = self::getBookQuery(self::bookCondByAll($value),$order,$page);
				break;
			case "competition":
				if (empty($order)) {
					$order = "expiration";
				}
				$link = "competition.php?action=readOne&comp=";
				$sql = self::getCompetitionQuery(self::compCondByAll($value),$order,$page);
				break;
			case "writing":
				if (empty($order)) {
					$order = "date DESC";
				}
				$link = "writing.php?action=readOne&id=";
				$sql = self::getWritingQuery(self::writingCondByAll($value),$order,$page);
				break;
		}		
		$query = MySQL::query($sql,__FILE__,__LINE__);
		//jestliže je vracen jen jeden radek, pak uzivatele automaticky presmeruje
		if(mysql_num_rows($query) == 1 AND $redirect){
			$data = mysql_fetch_object($query);
			header("Location: ".$link.$data->id."");
		} else {
  			return $query;
		}
	}
	
	/**
	 * 			Vytvori podminku pro hledani podle vsech kriterii u vlastni tvorby,
	 * @param 	mixed	Hledana fraze.
	 * @return 	string
	 */
	private static function writingCondByAll($value) {
		$condition= "(".self::writingCondByTag($value).")";
		return $condition;
	}
	
	/**
	 * 			Vytvori podminku pro vyhledavani klicovych slov ve vlastni tvorbe.
	 * @param	mixed	Hledana fraze.
	 * @return	string
	 */
	private static function writingCondByTag($value) {
		foreach ($value as $item) {
			if ($condition) {
				$condition .= " AND ";
			}
			$condition .= Writing::getTableName().".id IN (
				SELECT ".TagReference::getTableName().".target AS comp
				FROM ".TagReference::getTableName()."
				LEFT JOIN ".Tag::getTableName()." ON ".TagReference::getTableName().".tag = ".Tag::getTableName().".id
				WHERE
					".Tag::getTableName().".asciiName LIKE '%$item%'
				AND 
					".TagReference::getTableName().".type = 'writing'
			)";
		}
		return $condition;
	}	
}
?>