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
abstract class MySQLTableDiscussion {

	/**
	* @var string Prefix tabulky v databazi.
	*/
	const PREFIX = "reader_";

	/**
	* @var string Nazev tabulky v databazi
	*/
	const TABLE_NAME = "discussion";
	
	/**
	* @var array_string Nazvy poli tabulku v databazi, ktera se vzdy musi vyplnit.
	*/
	private static $importantColumns = array("user","text","date");
	
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
		MySQL::query(self::getTableName(),$change,array(0,self::getTableName().".id = $id"));
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
		MySQL::query($sql);
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
 	protected static function getCommonItems() {
 		return "
	    		".self::getTableName().".id,
	    		".self::getTableName().".topic,
	    		".self::getTableName().".title,
	    		".self::getTableName().".text,
	    		".self::getTableName().".date,
	    		".User::getTableName().".id AS userID,
	    		".User::getTableName().".name AS userName,
	    		".Topic::getTableName().".name
 		";
 	}
 	
	/**
	* Vrati bezne pripojovani tabulek v SQL dotazech.
	* @return string
	*/
	protected static function getCommonJoins() {
		return "
			LEFT JOIN ".User::getTableName()." ON ".self::getTableName().".user = ".User::getTableName().".id
			INNER JOIN ".Topic::getTableName()." ON ".self::getTableName().".topic = ".Topic::getTableName().".id
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
