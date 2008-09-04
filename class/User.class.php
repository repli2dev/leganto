<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/
/**
* Trida, ktera pracuje s tabulkou urcenou pro ukladani uzivatelu.
* @package reader
*/
class User extends MySQLTableUser {

       /**
       * @var int Cislo oznacujici uroven administratora.
       */
       const LEVEL_ADMIN = 4;
       
       /**
       * @var int Cislo oznacujici uroven BAN.
       */
       const  LEVEL_BAN = 1;

       /**
       * @var int Cislo oznacujici uroven bezneho uzivatele.
       */
       const LEVEL_COMMON = 2;

       /**
       * @var int Cislo oznacujici uroven moderatora.
       */
       const LEVEL_MODERATOR = 3;
       
       /**
       * @var int Pocet vracenych polozek, je-li vracen seznam.
       */
       CONST LIST_LIMIT = 50;

       /**
       * @var int Pocet vracenych polozek u mensich seznamu.
       */
       const SMALL_LIST_LIMIT = 30;

       /**
       * @var string Nazev tabulky.
       */
       const TABLE_NAME = "user";

       /**
       * @var array_string Nazvy poli tabulku v databazi, ktera se vzdy musi vyplnit.
       */
       private static $importantColumns = array("name","password","email");

       /**
       * Nastavi BAN/Odstrani BAN danemu uzivateli.
       * @param int ID uzivatele.
       * @return void
       */
       public static function ban($userID) {
               try {
                       $user = self::getInfo($userID);
                       if ($user->level == self::levelBan) {
                               $level = self::levelCommon;
                       }
                       else {
                               $level = self::levelBan;
                       }
                       $change = array("level" => $level);
                       self::change($userID,$change);
               }
               catch (error $exception) {
                       $exception->scream();
               }

       }

       /**
       * Zmeni informace o uzivateli.
       * @param int ID uzivatele.
       * @param mixed Pole zmen (pokud bude obsahovat polozku 'password', musi obsahovat i polozku 'password_control').
       * @return void
       */
       public static function change($id,$changes) {
               try {
                       if (isset($changes["password"])) {
                               if ($changes["password"] != $changes["password_control"]) {
                                       throw new Error(Lng::WITHOUT_PASSWORD_AGREEMENT);
                               }
                               $changes["password"] = sha1($changes["password"]);
                               unset($changes["password_control"]);
                       }
                       parent::change($id,$changes);
               }
               catch (Error $exception) {
                       $exception->scream();
               }
       }

       /**
       * Zmeni ikonku prihlaseneho uzivatele.
       * @param FILES_array
       * @return boolean
       */
       public static function changeIco($image) {
               try {  
                       $owner = Page::session("login");
                       Piko::setDirectory("./image/ico/");
                       Piko::setQuality(100);
                       Piko::setMaxWidth(100);
                       Piko::setMaxHeight(100);
                       Piko::setMaxSize(1000000);
                       Piko::setType("jpg");
                       if (!Piko::work($image,$owner->id)) {
                               throw new Error(Lng::WRONG_IMG_TYPE);
                       }
                       else {
							return TRUE;
                       }
                }
               catch (Error $exception) {
                       $exception->scream();
                       return FALSE;
               }
       }

       /**
       * Vytvori uzivatele v databazi.
       * @param mixed Pole vstupu (musi navic obsahovat i polozku 'password_control').
       * @return boolean
       */
       public static function create($input) {
               try {
                       if ($input["password"] != $input["password_control"]) {
                               throw new Error(Lng::WITHOUT_PASSWORD_AGREEMENT);
                       }
                       else {
                               $input["password"] = sha1($input["password"]);
                               unset($input["password_control"]);
                       }
                       $input["asciiName"] = self::simpleName($input["name"]);
                       $user = self::getInfoByItem(self::getTableName().".asciiName",$input["asciiName"]);
                       if ($user->id) {
                               throw new Error(Lng::USER_EXISTS);
                       }
                       $input["login"] = "now()";
                       $input["level"] = 2;
                       parent::create($input);
                       $usID = mysql_insert_id();
                       $_SESSION[login] = self::getInfo($usID);
                       return TRUE;
               }
               catch (Error $exception) {
                       $exception->scream();
                       return FALSE;
               }
       }
       
       /**
       * Odstrani uzivatele z databaze.
       * @param int ID uzivatele.
       * @return void
       */
       public static function destroy($user) {
               try {
                       $owner = Page::session("login");
                       if ($level != self::levelAdmin) throw new Error(Lng::ACCESS_DENIED);
                       parent::destroy($user);
                       $cond = array(0 => "user = $user");
                       Opinion::destroyByCond($cond);
                       Comment::destroyByCond($cond);
               }
               catch (error $exception) {
                       $exception->scream();
               }
       }
       /**
       * Vrati jmena vsech uzivatelu.
       * @return mysql_reuslt
       */
       public static function getAllName() {
               $sql = "SELECT name FROM ".self::getTableName()." ORDER BY name";
               $res = MySQL::query($sql);
               $result = array();
               while($user = mysql_fetch_object($res)) {
					$result[] = $user->name;
               }
               return $result;
       }

       /**
       * Vrati bezne pozadovane polozky pro SQL dotaz.
       * @return string
       */
       protected static function getCommonItems() {
               return "
                       ".self::getTableName().".id,
                       ".self::getTableName().".name AS name,
                       ".self::getTableName().".email,
                       ".self::getTableName().".description,
                       ".self::getTableName().".level,
                       ".self::getTableName().".login,        
                       ".self::getTableName().".remember,
                       (((SELECT COUNT(".Recommend::getTableName().".id) FROM ".Recommend::getTableName()." WHERE ".Recommend::getTableName().".recommend = ".self::getTableName().".id) + 1)*(COUNT(".Opinion::getTableName().".id))) AS recommend
               ";
       }

       /**
       * Vrati bezne pripojovani tabulek v SQL dotazech.
       * @return string
       */
       protected static function getCommonJoins() {
               return "
                       LEFT JOIN ".Opinion::getTableName()." ON ".self::getTableName().".id = ".Opinion::getTableName().".user
               ";
       }

       /**
       * Vrati informace (krome hesla) o uzivateli.
       * @param string Nazev polozky.
       * @param value Hodnota polozky.
       * @return record
       */
       public static function getInfoByItem($item,$value) {
               $get = parent::getInfoByItem($item,$value);
               $get->password = "";
               return $get;
       }
       
	/**
	 * Vrati podobne uzivatele prihlaseneho uzivatele.
	 * @return mysql_result
	 */   
	public static function getSimilar() {
		$owner = Page::session("login");
		$sql = "
			SELECT
				".self::getCommonItems().",
				".UserSim::getTableName().".similarity AS sim
			FROM ".self::getTableName()."
			".self::getCommonJoins()."
			INNER JOIN ".UserSim::getTableName()." ON ".self::getTableName().".id = ".UserSim::getTableName().".user
			WHERE ".UserSim::getTableName().".owner = $owner->id
			GROUP BY ".self::getTableName().".id
			ORDER BY ".UserSim::getTableName().".similarity DESC
			LIMIT 0,".UserSim::LIST_LIMIT."
		";
		return MySQL::query($sql,__FILE__,__LINE__);
	}
       
       /**
       * Vytvori tabulku v databazi
       * @return void
       */
       public static function install() {
               $sql = "CREATE TABLE ".self::getTableName()." (
                       id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
                       name VARCHAR(32) UNIQUE NOT NULL,
                       asciiName VARCHAR(32) UNIQUE NOT NULL,
                       password VARCHAR(128) NOT NULL,
                       email VARCHAR(128) NOT NULL,
                       description TEXT DEFAULT '',
                       level ENUM('1','2','3','4') DEFAULT '2',
                       login DATETIME default '0000-00-00 00:00:00',
                       remember VARCHAR(256) NOT NULL DEFAULT '0'
               )";
               MySQL::query($sql,__FILE__,__LINE__);
       }
       
       /**
       * Vrati seznam uzivatelu.
       * @param string Polozka, podle ktere se vysledek seradi.
       * @param int Cislo zobrazovane stranky.
       * @param string Jmeno uzivatele, ktere je hledano
       * @return mysql_result
       */
       public static function listAll($order,$page,$name = "") {
			if (empty($order)) {
				$order = "login DESC";       		
			}
               $name = String::cut(String::utf2lowerAscii($name));
               $condition = "";
               foreach($name AS $item) {
                       if ($condition) $condition .= " AND ";
                       $condition .= self::getTableName().".asciiName LIKE '%$item%'";  
               }
               $limit = $page*self::LIST_LIMIT;  
               $sql = "
                       SELECT
                               ".self::getCommonItems()."
                       FROM ".self::getTableName()."
                       ".self::getCommonJoins()."
                       WHERE $condition
                       GROUP BY ".self::getTableName().".id
                       ORDER BY $order
                       LIMIT $limit,".self::LIST_LIMIT
               ;
               $res = MySQL::query($sql,__FILE__,__LINE__);
               return $res;
       }

	/**
	* Prihlasi uzivatele.
	* @param string Jmeno uzivatele.
	* @param string Heslo uzivatele.
	* @return boolean
	*/
	public function logIn($name,$password,$remember) {
		//die ($remember);
		$password = sha1($password);
		$sql = "SELECT id,password FROM ".self::getTableName()." WHERE asciiName = '".self::simpleName($name)."'";
		$res = MySQL::query($sql,__FILE__,__LINE__);
		$user = mysql_fetch_object($res);
		try {
			if (!$user->id) throw new Error(Lng::WRONG_NAME);
			if ($user->password != $password) throw new Error(Lng::WRONG_PASSWORD);
			if ($remember) {
				$rememberHash = String::random(256);
				SetCookie("rememberLoginID",$user->id,time() + 60*60*24*14);
				SetCookie("rememberLoginHash",$rememberHash, time() + 60*60*24*14);
			}
			self::change($user->	id,array("login" => "now()", "remember" => $rememberHash));
			$user = User::getInfo($user->id);
			Page::setSession("login",$user);
		}
		catch (error $exception) {
			$exception->scream();
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * Zkusi uzivatele prihlasit na zaklade cookie.
	 * @return boolean
	 */
	public static function logInIfRemebered() {
		if (isset($_COOKIE["rememberLoginID"])) {
			$user = User::getInfo($_COOKIE["rememberLoginID"]);
			if (($_COOKIE["rememberLoginHash"] == $user->remember) && ($user->remember != '0')) {
				$rememberHash = String::random(256);
				SetCookie("rememberLoginHash",$rememberHash, time() + 60*60*24*14);
				self::change($user->id,array("login" => "now()", "remember" => $rememberHash));
				$user = User::getInfo($user->id);
				Page::setSession("login",$user);
				return TRUE;
			}
		}
		else {
			return FALSE;
		}
	}
	
	/**
	 * Odhlasi uzivatele.
	 * @return void 
	 */
	public static function logOut() {
		$owner = Page::session("login");
		if (isset($owner->id)) {
			self::change($owner->id,array("remember" => 0));
			session_destroy();
			unset($_COOKIE["rememberLoginID"]);
			unset($_COOKIE["rememberLoginHash"]);
		}
		unset($owner);
	}
	
       /**
       * Prevede jmeno uzivatele na podobu, kterou lze pouzit v URL.
       * @param string Jmeno uzivatele.
       * @return string Jmeno uzivatele.
       */
       public static function simpleName($name) {
               $change = array(" "=>"-");
               return strtr(String::utf2lowerAscii($name),$change);
       }

       /**
       * Vrati uzivatele s nejvetsi "karmou" (hodnocenim)
       * @return mysql_result
       */
       public function topList() {
               $sql = "
                       SELECT
                               ".self::getTableName().".id AS usID
                       FROM ".self::getTableName()."
                       ".self::getCommonJoins()."
                       GROUP BY usID
                       ORDER BY ((SELECT COUNT(".Recommend::getTableName().".id) FROM ".Recommend::getTableName()." WHERE ".Recommend::getTableName().".recommend = usID) + 1)*((SELECT COUNT(".Opinion::getTableName().".id) FROM ".Opinion::getTableName()." WHERE ".Opinion::getTableName().".user = usID)) DESC
                       LIMIT 0,".self::SMALL_LIST_LIMIT
               ;
               $res = MySQL::query($sql,__FILE__,__LINE__);
               $help = "";
               while ($record = mysql_fetch_object($res)) {
                       if ($help) {
                               $help .= ", ";
                       }
                       $help .= $record->usID;  
               }
               if ($help) {
	               $sql = "
	                       SELECT
	                               ".self::getCommonItems()."
	                       FROM ".self::getTableName()."
	                       ".self::getCommonJoins()."
	                       WHERE ".self::getTableName().".id IN ($help)
	                       GROUP BY ".self::getTableName().".id
	                       ORDER BY ".self::getTableName().".name
	               ";
               }
               return MySQL::query($sql,__FILE__,__LINE__);
       }

}
?>
