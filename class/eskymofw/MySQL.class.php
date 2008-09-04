<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* Tato trida slouzi pro komunikaci s MySQL databazi.
* @package eskymoFW
*/
class MySQL {
	
	/**
	* @var boolean Promenna, ktera urcuje, zda je trida v testovacim rezimu (TRUE) nebo ne (FALSE). Nefunguje, pokud jsou zapnuty vyjimky.
	*/
	private static $switcherTest = TRUE;

	/**
	* @var boolean Zapne/Vypne predavani vyjimek na vyssi uroven.
	*/
	private static $switcherException = FALSE;

	/**
	* @var string Server, na kterem bezi databaze.
	*/
	private static $server = "localhost:3306";
	
	/**
	* @var string Nazev databaze, se kterou pracuji.
	*/
	private static $database = "reader";

	/**
	* @var string Jmeno uzivatele databaze.
	*/
	private static $user = "reader_cz";

	/**
	* @var string Heslo k databazi.
	*/
	private static $password = "terka90";

	/**
	* @var string Porovnavani spojeni.
	*/
	private static $character = "utf8";

	/**
	* Konstruktor.
	* @return void
	*/
	public function __construct() {
	}

	/**
	* Pripoji se k databazi.
	* @return boolean
	*/
	public static function connect() {
		if (self::getException()) {
			try {
				$help = self::connectWithException();
			}
			catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
			return $help;
		}
		else return self::connectWithoutException();
	}

	/**
	* Pripoji se k databazi a v pripade chyby vyhodi vyjimku.
	* @return boolean
	*/
	private static function connectWithException() {
		$help = TRUE;
		try {
			if (!(mysql_pconnect(self :: getServer(),self :: getUser(), self :: $password))) {
				$help = FALSE;
				throw new Exception(Language :: error . " : " . __FILE__ . " : " . __LINE__ . Language :: noDBServer);
			}
			if (!(mysql_select_db(self :: getDatabase()))) {
				$help = FALSE;
				throw new Exception(Language :: error . " : " . __FILE__ . " : " .__LINE__ . " : " . Language :: noDatabase);
			}
			if (!(mysql_query("SET CHARACTER SET ". self :: getCharacter()))) {
				$help = FALSE;
				throw new Exception(Language :: error . " : " . __FILE__ . " : " . __LINE__ . " : " . Language :: wrongCharset);
			}
		}
		catch(Exception $e) {
			throw new Exception($e->getMessage);
		}
		return $help;
	}

	/**
	* Pripojit se k databazi, aniz by predaval vyjimku na vyssi uroven.
	* @return boolean
	*/
	private static function connectWithoutException() {
		if (self :: getTest()) {		
			mysql_pconnect(self :: getServer(),self :: getUser(), self :: $password) or die(Language :: ERROR . " : " . __FILE__ . " : " . __LINE__ . Language :: NO_DB_SERVER);
			mysql_select_db(self :: getDatabase()) or die(Language :: ERROR . " : " . __FILE__ . " : " .__LINE__ . " : " . Language :: NO_DATABASE);
			mysql_query("SET CHARACTER SET ". self :: getCharacter()) or die(Language :: ERROR . " : " . __FILE__ . " : " . __LINE__ . " : " . Language :: WRONG_CHARSET);
			return TRUE;
		}
		else {
			try {
				if (!(mysql_pconnect(self :: getServer(),self :: getUser(), self :: $password))) {
					throw new Exception;
				}
				if (!(mysql_select_db(self :: getDatabase()))) {
					throw new Exception;
				}
				if (!(mysql_query("SET CHARACTER SET ". self :: getCharacter()))) {
					throw new Exception;
				}
				return TRUE;
				
			}
			catch (Exception $e) {
				return FALSE;
			}
		}
	}
	
	
	/**
	* Vrati databazovy server.
	* @return string
	*/
	public static function getServer() {
 		return self :: $server;
	}
	
	/**
	* Vrati nazev databaze.
	* @return string
	*/	
	public static function getDatabase() {
		return self :: $database;
	}
	
	/**
	* Vrati jmeno uzivatele databaze.
	* @return string 
	*/
	public static function getUser() {
		return self :: $user;
	} 
	
	/**
	* Vrati pouzitou znakovou sadu pripojeni (napr. utf8).
	* @return string
	*/
	public static function getCharacter() {
		return self :: $character;
	}
	
	/**
	* Vrati TRUE, pokud je trida v testovacim rezimu.
	* @return boolean
	*/	
	public static function getTest() {
		return self :: $switcherTest;
	}
	
	/**
	* Vrati TRUE pokud je nastaveno predavani vyjimek na vyssi uroven.
	* @return boolean
	*/
	public static function getException() {
		return self::$switcherException;
	}

	/**
	* Nastavi databazovy server.
	* @param string
	* @return void
	*/
	public static function setServer($server) {
		self :: $server = $server;
	}
	
	/**
	* Nastavi nazev databaze.
	* @param string
	* @return void
	*/
	public static function setDatabase($database) {
		self :: $database = $database;
	}
	
	/**
	* Nastavi jmeno uzivatele.
	* @param string
	* @return void
	*/
	public static function setUser($user) {
		self :: $user = $user;
	}
	
	/**
	* Nastavi heslo.
	* @param string
	* @return void
	*/
	public static function setPassword($password) {
		self :: $password = $password;
	}

	/**
	* Nastavi znakovou sadu (porovnavani) pripojeni (napr. utf8).
	* @param string
	* @return void
	*/
	public static function setCharacter($character) {
		self :: $character = $character;
	}
	
	/**
	* Nastavi testovaci rezim.
	* @param boolean
	* @return void
	*/	
	public static function setTest($test) {
		self :: $switcherTest = $test;
	}
	
	/**
	* Nastavi tridu na predavani vyjimek na vyssi uroven.
	* @param boolean
	* @return void
	*/
	public static function setException($e) {
		self::$switcherException = $e;
	}

	/**
	* Polozi dotaz databazi.
 	* @param string SQL dotaz.
	* @param string Soubor, ktery vola metodu.
	* @param int Radek, ze ktereho je metoda volana.
	* @return boolean
	*/
	public static function query($sql,$file = NULL, $line = NULL) {
		if (self::getException()) {
			try {
				$help = self::queryWithException($sql,$file,$line);
				return $help;
			}
			catch(Exception $e) {
				throw new Exception($e->getMessage());
				return $help;
			}
		}
		else {
			return self::queryWithoutException($sql,$file,$line);
		}
	}

	/**
	* Polozi dotaz databazi a v pripade chyby preda vyjimku na vyssi uroven.
 	* @param string SQL dotaz.
	* @param string Soubor, ktery vola metodu.
	* @param int Radek, ze ktereho je metoda volana.
	* @return boolean
	*/
	private static function queryWithException($sql,$file = NULL, $line = NULL) {
		try {		
			if (!($help = mysql_query($sql))) {
				throw new Exception(Language::ERROR . " : " . $file . " : " . $line . " : " . $sql);
			}
			return $help;
		}
		catch(Exception $e) {
			throw new Exception($e->getMessage());
			return $help;
		}
	}

	/**
	* Formatuje vkladane hodnoty (pridava uvozovky).
	* @param value
	* @return value
	*/
	public static function formatValue($value) {
		if ((gettype($value) != "integer") and (gettype($value) != "double") and ($value != "now()")) {
			$value = "'$value'";
		}
		return $value;
	}

	/**
	* Polozi dotaz databazi, aniz by predaval vyjimku na vyssi uroven.
	* @param string SQL dotaz.
	* @param string Soubor, ktery vola metodu.
	* @param int Radek, ze ktereho je metoda volana.
	* @return boolean
	*/
	private static function queryWithoutException($sql,$file = NULL, $line = NULL) {
		if (self :: getTest()) {
			$ret = mysql_query($sql) or die (Language::ERROR . " : " . $file . " : " . $line . " : " . $sql);
			return $ret;
		}
		else {
			return mysql_query($sql);
		}
	}

	/**
	* Vytvori a provede INSERT dotaz
	* @param string Nazev tabulky
	* @param array_string Pole vkladanych hodnot, indexy oznacuji nazvy prislusnych sloupcu.
	*/
	public static function insert($tableName,$input) {
		$column = "";
		$value = "";
		foreach ($input AS $key => $item) {
			if ($column != "") {
				$column .= ", ";
				$value .= ", ";
				
			}
			$column .= $key;
			$value .= self::formatValue($item);
		}
		$sql = "INSERT INTO $tableName ($column) VALUES($value)";
		MySQL::query($sql,__FILE__,__LINE__);		
	}

	/**
	* Vytvori a provede UPDATE dotaz
	* @param string Nazev tabulky
	* @param array_string Pole zmen, indexy oznacuji nazvy prislusnych sloupcu.
	* @param array_string Pole podminek, indexy oznacuji operator s predchozi podminkou. 
	* @return void
	*/
	public static function update($tableName,$changes,$cond) {
		$update = "";
		foreach ($changes AS $key => $item) {
			if ($update != "") {
				$update .= ", ";
			}
			$update .= "$key = ". self::formatValue($item);
		}
		$condition = "";
		foreach ($cond AS $key => $item) {
			if ($condition != "") {
				$condition .= " $key ";
			}
			$condition .= $item;
		}
		$sql = "UPDATE $tableName SET $update WHERE $condition";
		MySQL::query($sql,__FILE__,__LINE__);
	}
}
?>
