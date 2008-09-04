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
	 * Vytvori podminku pro hledani podle vsech kriterii.
	 * @param string Hledana fraze.
	 * @return string
	 */
	private static function conByAll($value) {
		$condition = "(".self::conByBook($value).") OR (".self::conByWriter($value).") OR (".self::conByTag($value).")";
		return $condition; 
	}

	/**
	 * Vytvori podminku pro vyhledavani v knihach.
	 * @param string Hledana fraze.
	 * @return string
	 */
	private static function conByBook($value) {
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
	 * Vytvori podminku pro hledani v klicovych slovech.
	 * @param string Hledana fraze.
	 * @return string
	 */
	private static function conByTag($value) {	
		foreach ($value as $item) {
			if ($condition) {
				$condition .= " AND ";
			}
			$condition .= Book::getTableName().".id IN (
				SELECT ".TagReference::getTableName().".book
				FROM ".TagReference::getTableName()."
				LEFT JOIN ".Tag::getTableName()." ON ".TagReference::getTableName().".tag = ".Tag::getTableName().".id
				WHERE ".Tag::getTableName().".asciiName LIKE '%$item%'
			)";  
  		}
		return $condition;
	}
 
	/**
	 * Vytvori podminku pro vyhledavani v autorech.
	 * @param string Hledana fraze
	 * @return string
	 */
	private static function conByWriter($value) {
		$list = String::cut(String::utf2ascii($writer));
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
	 * Vrati SQL dotaz pro vyhledavani.
	 * @param string Podminka.
	 * @param string Polozka, podle ktere bude vysledek serazen.
	 * @param int Cislo zobrazovane stranky.
	 * @return string
	 */
	private static function getQuery($condition,$order,$page) {
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
	 * Vrati polozky na zaklade hledane fraze.
	 * @param string Typ vyhledavani ('bookTitle','writer','tag','all').
	 * @param string Hledana fraze.
	 * @param string Polozka, podle ktere bude vysledek serazen ('rating','readed','').
	 * @param int Cislo zobrazovane stranky.
	 * @return mysql_result
	 */
	public function search($type,$value,$order,$page) {
		switch($order) {
			case "":
				$order = "rating DESC,countRead DESC";
				break;
			case "rating DESC":
				$order .= ",countRead DESC";
				break;
		}
		$value = String::cut(String::utf2ascii($value));
		if (!$page) $page = 0; 
		switch ($type) {
			case "bookTitle":
				$condition = self::conByBook($value);
				break;
			case "writer":
				$condition = self::conByWriter($value);
				break;
			case "tag":
				$condition = self::conByTag($value);
				break;
			default:
				$condition = self::conByAll($value);
				break;
		}
		$sql = self::getQuery($condition,$order,$page);
  		return MySQL::query($sql,__FILE__,__LINE__);
	}
}
?>