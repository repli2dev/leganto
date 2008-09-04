<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Trida slouzici jako predek pro vsechny tridy, ktere obsluhuji tabulky v databazi.
* @example doc_example/MySQLTable.phps
*/
abstract class MySQLTableOpinion {

	/**
	* @var string Prefix tabulky v databazi.
	*/
	const PREFIX = "reader_";

	/**
	* @var string Nazev tabulky v databazi
	*/
	const TABLE_NAME = "opinion";

	/**
	* @var array_string Nazvy poli tabulku v databazi, ktera se vzdy musi vyplnit.
	*/
	public static $importantColumns = array();

	/**
	* Vrati nazev tabulky (i s prefixem).
	* @return void
	*/
	public static function getTableName() {
		return (self::PREFIX . self::TABLE_NAME);

	}

	/**
	* Zmeni informace o polozce.
	* @param int ID polozky.
	* @param array_string Pole pozadovanych zmen, index predstavuji nazvy sloupcu tabulky v databazi, hodnoty pozadavane hodnoty.
	* @return void
	*/
	public static function change($id,$change) {
		MySQL::update(self::getTableName(),$change,array(0 =>self::getTableName().".id = $id"));
	}

	/**
	* Kontroluje, zda vstup obsahuje vsechna povinna pole
	* @see MySQL::$importantColumns
	* @param array_string
	* @return boolean
	*/
	private static function checkInput($input) {
		foreach(self::$importantColumns AS $column) {
			if (empty($input[$column])) {
				return FALSE;
			}
		}
		return TRUE;
	}

	/**
	* Vytvori polozku.
	* @param array_string Pole vkladanych hodnot, nazvy indexu predstavuji nazvy sloupcu v db.
	* @return mysql_record
	*/
	public static function create($input) {
		try {		
			if (self::checkInput($input)) {
				MySQL::insert(self::getTableName(),$input);
			}
			else {
				throw new Error(Language::WITHOUT_IMPORTANT_COLUMN);
			}	
			return self::getInfo(mysql_insert_id());
		}
		catch(Error $e) {
			$e->scream();
			return FALSE;			
		}
	}

	/**
	* Odstrani polozku z databaze.
	* @param int ID polozky
	* @return void
	*/
	public static function destroy($id) {
		$cond = array(0 => "id = $id");
		self::destroyByCond($cond);
	}

	/**
	* Odstrani polozky na zaklade podminek.
	* @param mixed Pole podminek, kde indexy oznacuji zpojku s predchozi podminkou.
	* @return void
	*/
	public static function destroyByCond($cond) {
		$condition = "";
		foreach ($cond AS $key => $item) {
			if ($condition != "") {
				$condition .= " $key ";
			}
			$condition .= $item;
		}
		$sql = "DELETE FROM ".self::getTableName()." WHERE $condition";
		MySQL::query($sql,__FILE__,__LINE__);
	}

	/**
	* Vrati informace o polozce.
	* @param int ID polozky.
	* @return record
	*/
	public static function getInfo($id) {
		return self::getInfoByItem(self::getTableName().".id",$id);
	}
	
	/**
	* Vrati informace o polozce.
	* @param string nazev sloupce
	* @param value hodnota sloupce
	* @return record
	*/
	public static function getInfoByItem($item,$value) {
		$value = MySQL::formatValue($value);
		$cond = array (0 => "$item = $value");
		return self::getInfoByCond($cond);
	}

	/**
	* Vrati informace o polozce.
	* @param mixed Podminky SQL dotazu.
	* @return record
	*/
	public static function getInfoByCond($cond) {
		$sql = self::getCommonQuery($cond,self::getTableName().".id",0,1);
		return mysql_fetch_object(MySQL::query($sql,__FILE__,__LINE__));		
	}

	/**
	* Vytvori tabulku v databazi.
	* @return void
	*/
	abstract public static function install();

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
	* Vrati SQL dotaz typu SELECT.
	* @param mixed Podminky SQL dotazu.
	* @param strign Polozka podle, ktere se vysledek seradi.
	* @param int Pocatecni hodnota limitu.	
	* @param int Pocet vracenych polozek (LIMIT).
	* @return string SQL dotaz.
	*/
	protected static function getCommonQuery($cond,$order,$limit1,$limit2) {
		$condition = "";
		foreach ($cond AS $key => $item) {
			if ($condition != "") {
				$condition .= " $key ";
			}
			$condition .= $item;
		}
		if ($condition != "") {
			$condition = "WHERE $condition";
		}
		return "
	   		SELECT
				" . self::getCommonItems() ."
			FROM ".self::getTableName()."
			" . self::getCommonJoins() . "
			$condition
			GROUP BY ".self::getTableName().".id
			ORDER BY $order	
			LIMIT $limit1,$limit2
		";		
	}
	
	/**
	* Provede SQL dotaz typu SELECT.
	* @param mixed Podminka SQL dotazu.
	* @param strign Polozka podle, ktere se vysledek seradi.
	* @param int Pocatecni hodnota limitu.	
	* @param int Pocet vracenych polozek (LIMIT).
	* @return mysql_result
	*/
	protected static function makeCommonQuery($cond,$order,$limit1,$limit2) {
		$sql = self::getCommonQuery($cond,$order,$limit1,$limit2);
		return MySQL::query($sql,__FILE__,__LINE__);
	}
}
?>
