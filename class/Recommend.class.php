<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/

/**
* Tato trida je urcena pro praci s tabulkou, kde jsou ulozeny vztahy mezi uzivateli.
* @package reader
*/
class Recommend extends MySQLTableRecommend {	

	/**
	* @var int Pocet zobrazovanych komentaru.
	*/
 	const LIST_LIMIT = 1000;

 	/**
	* @var string Nazev tabulky.
 	*/
    const TABLE_NAME = "recommend";

	/**
	* @var array_string Nazvy poli tabulku v databazi, ktera se vzdy musi vyplnit.
	*/
	protected static $importantColumns = array("user","recommend");	
	
	/**
	 * Vytvori v databazi spojeni mezi uzivateli (mezi prihlasenym a danym uzivatelem)
	 * @param int ID uzivatele.
	 * @return void
	 */
	public static function create($user) {
		try {
			$owner = Page::session("login");
			if (!$owner->id) throw new Error(Lng::ACCESS_DENIED);
			$sql = "SELECT id FROM ".self::getTableName()." WHERE user = $owner->id AND recommend = $user";
			$res = MySQL::query($sql,__FILE__,__LINE__);
			$record = mysql_fetch_object($res);
			if ((!$record->id) and ($user != $owner->id)) {
				parent::create(array("user" => $owner->id,"recommend" => $user));
			}
		}
		catch (Error $exception) {
			$exception->scream();
		}
	 }	
	
	/**
	 * Smaze spojeni mezi danym uzivatelem a prihlasenym uzivatelem.
	 * @param int ID uzivatele
	 * return void
	 */
	public static function destroyMine($usID) {
		$owner = Page::session("login");
		self::destroy($owner->id,$usID); 
	}	 

	/**
	 * Smaze spojeni mezi dvema uzivateli.
	 * @param int ID uzivatele, ktery "vlastni" spojeni.
	 * @param int ID uzivatele, ktery je obliben.
	 * @return void
	 */
	public static function destroy($owner,$recommend) {
		self::destroyByCond(array(0 => "user = $owner", "AND" => "recommend = $recommend"));
	}

       /**
       * Vrati bezne pozadovane polozky pro SQL dotaz.
       * @return string
       */
       protected static function getCommonItems() {
               return 
					User::getTableName().".id,
               		".User::getTableName().".name,
		    		".User::getTableName().".email,
		    		".User::getTableName().".description,
					".User::getTableName().".level,
					".User::getTableName().".login,
					COUNT(".self::getTableName().".id) AS recommend			
				";
       }

       /**
       * Vrati bezne pripojovani tabulek v SQL dotazech.
       * @return string
       */
       protected static function getCommonJoins() {
               return "
                       INNER JOIN ".User::getTableName()." ON ".self::getTableName().".recommend = ".User::getTableName().".id
               ";
       }
	
	/**
	 * Vytvori tabulku v databazi.
	 * @return void
	 */
	public static function install() {
		$sql = "CREATE TABLE ".self::getTableName()." (
			id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
			user INT(25) NOT NULL,
			recommend INT(25) NOT NULL,
			FOREIGN KEY (user) REFERENCES ".User::getTableName()." (id),
			FOREIGN KEY (recommend) REFERENCES ".User::getTableName()." (id)
		)";
		MySQL::query($sql,__FILE__,__LINE__);
	}

	/**
	 * Zjisti zda ma prihlaseny uzivatel daneho uzivatele v oblibenych.
	 * @param int ID uzivatele.
	 * @return boolean
	 */
	public static function isMine($usID) {
		$owner = Page::session("login");
		$sql = "SELECT id FROM ".self::getTableName()." WHERE user = $owner->id AND recommend = $usID";
		$res = MySQL::query($sql,__FILE__,__LINE__);
		$record = mysql_fetch_object($res);
		if ($record->id) return true;
		else return false;
	} 
	
	/**
	 * Vrati obliebene uzivatele daneho uzivatele.
	 * @param int ID uzivatele.
	 * @return mysql_result
	 */
	public static function byUser($userID) {
		$sql = "
			SELECT
				".self::getCommonItems()."
			FROM ".self::getTableName()."
			".self::getCommonJoins()."
			WHERE ".self::getTableName().".user = $userID
			GROUP BY ".User::getTableName().".id
			ORDER BY name
		";
		return MySQL::query($sql,__FILE__,__LINE__);
	}
	
	/**
	 * Vrati uzivatele, kteri maji daneho uzivatele v oblibenych.
	 * @var int ID uzivatele
	 * @return mysql_result
	 */
	public static function meInOthers($usID) {
		//if (gettype($usID) == "integer") {
			$sql = "
				SELECT
				".self::getCommonItems()."
				FROM ".self::getTableName()."
				LEFT JOIN ".User::getTableName()." ON ".self::getTableName().".user = ".User::getTableName().".id
				WHERE ".self::getTableName().".recommend = $usID
				GROUP BY ".User::getTableName().".id
				ORDER BY ".User::getTableName().".name			
			";
			return MySQL::query($sql,__FILE__,__LINE__); 
		//}
	}
}
