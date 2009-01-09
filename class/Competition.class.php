<?php
/**
 * @package		reader
 * @author 		Jan Papousek
 * @copyright 	Internetovy ctenarsky denik
 * @link 		http://ctenari.cz 
 */

/**
 * 				Trida, ktera se stara o literarni souteze.
 * @package 	reader
 */
class Competition extends MySQLTableCompetition {
	
	const LIST_LIMIT = 10;
	
	/**
	 * 			Zmeni soutez (vymaze puvodni a vlozi novou).
	 * @param	int 	ID puvodni souteze.
	 * @param	mixed	Vstup (podobny jako u metody Competition::create()).
	 * @return	boolean	Vrati TRUE, pokud zmena probehla.
	 */
	public static function change($id,$changes) {
		try {
			$owner = Page::session("login");
			$comp = self::getInfo($id);
			if (((!$owner->id) || ($owner->level <= User::LEVEL_BAN)) && ($owner->id != $comp->userID)) {
				throw new Error(Lng::ACCESS_DENIED);
			}
			if (isset($changes["expiration"])) {
				if (String::isDate($changes["expiration"]) == false) {
					throw new Error(Lng::COMPETITION_EXPIRATION_INFO);
				}
				ereg("([0-9]{1,2})\.[ ]?([0-9]{1,2})\.[ ]?([0-9]{4})",$changes["expiration"],$regs);
				$changes["expiration"] = date("Y-m-H",mktime(1,1,1,$regs[1],$regs[2],$regs[3],0));
			}
			parent::change($id,$changes);
			return TRUE;
		}
		catch(Error $e) {
			$e->scream();
		}
	}
	
	/**
	 * 			Vytvori soutez.
	 * 
	 * @param
	 * @return int	Pokud se soutez vytvorila, vrati jeji ID.
	 */
	public static function create($input) {
		try {
			$owner = Page::session("login");
			if ((!$owner->id) || ($owner->level <= User::LEVEL_BAN)) {
				throw new Error(Lng::ACCESS_DENIED);
			}
			$input["user"] = $owner->id;
			if (String::isDate($input["expiration"]) == false) {
				throw new Error(Lng::COMPETITION_EXPIRATION_INFO);
			}
			ereg("([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})",$input["expiration"],$regs);
			$input["expiration"] = date("Y-m-H",mktime(1,1,1,$regs[1],$regs[2],$regs[3],0));
			$tags = $input["tags"];
			unset($input["tags"]);
			$last = parent::create($input);
			TagReference::create($tags,$last->id,"competition");
			return $last->id;
		}
		catch (Error $e) {
			$e->scream();
		}
	}
	
	/**
	 * 			Zrusi soutez.
	 * 
	 * @param 	int 	ID souteze.
	 */
	public static function destroy($id) {
		$comp = self::getInfo($id);
		$owner = Page::session("login");
		try {
			if (($owner->id != $comp->user) && ($owner->level <= User::LEVEL_COMMON)) {
				throw new Error(Lng::ACCESS_DENIED);
			}
			Discussion::destroyByFollow($id,"competition");
			TagReference::destroy($id,"competition");
			parent::destroy($id);
		}
		catch(Error $e) {
			$e->scream();
		}
	}
	
	/**
	 * 			Vrati naposledy vlozene souteze.
	 * @return	mysql_result
	 */
	public static function getLast() {
		$sql = "
			SELECT
				".self::getCommonItems()."
			FROM
				".self::getTableName()."
			".self::getCommonJoins()."
			WHERE
				expiration > now()
			GROUP BY ".self::getTableName().".id
			ORDER BY date DESC
		";
		return MySQL::query($sql,__FILE__,__LINE__);
	}
	
	/**
     * 			Vytvori tabulku v databazi.
     * @return 	void
     */
	public static function install() {
		$sql = "CREATE TABLE ".self::getTableName()." (
			id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
			user INT(25) NOT NULL,
			name TINYTEXT NOT NULL,
			content TEXT NOT NULL,
			date TIMESTAMP,
			expiration DATE DEFAULT '0000-00-00' ,
			FOREIGN KEY (user) REFERENCES ".User::getTableName()." (id)
		)";
		MySQL::query($sql,__FILE__,__LINE__);
	}
	
	/**
	 * 			Zjisti, zda prihlaseny uzivatel je ten, kdo pridal soutez.
	 * @param	int		ID souteze.
	 * @return 	boolean
	 */
	public static function isMine($id) {
		$owner = Page::session("login");
		$comp = self::getInfo($id);
		return ($comp->userID == $owner->id);
	}
	
	/**
	 * 			Precte souteze na dane strance.
	 * @param	int 	Cislo stranky
	 * @return 	mysql_result
	 */
	public static function read($page = 0) {
		if (empty($page)) {
			$page = 0;
		}
		return self::makeCommonQuery(array(0 => "expiration > now()"),self::getTableName().".date DESC",$page*(self::LIST_LIMIT),self::LIST_LIMIT);
	}
}
?>