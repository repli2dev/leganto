<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/

/**
* Tato trida je urcena pro praci s tabulkou, kde jsou ulozeny informace o podobnosti uzivatelu.
* @package reader
*/
class UserSim extends MySQLTableUserSim {

	const LIST_LIMIT = 10;
	
	/**
	* @var string Nazev tabulky v databazi.
	*/
	const TABLE_NAME = "userSim";

	/**
	* @var mixed Nazvy poli tabulku v databazi, ktera se vzdy musi vyplnit. 
	*/
	protected static $importantColumns = array("owner","user","similarity");	
	
	/**
	 * Vrati Podobnost prihlaseneho uzivatele s danym uzivatelem.
	 * @param int ID uzivatele.
	 * @return int
	 */
	public static function get($usID) {
		$owner = Page::session("login");
		$sql = "SELECT similarity FROM ".self::getTableName()." WHERE owner = $owner-> AND user = $usID";
		$res = MySQL::query($sql,__FILE__,__LINE__);		
		$record = mysql_fetch_object($res);
		return $record->similarity;		
	} 		
	
	/**
	 * Vytvori tabulku v databazi.
	 * @return void
	 */
	public static function install() {
 		$sql = "CREATE TABLE ".self::getTableName()." (
 			id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
 			owner INT(25) NOT NULL,
 			user INT(25) NOT NULL,
 			similarity INT(3) NOT NULL,
 			FOREIGN KEY (owner) REFERENCES ".User::getTableName()." (id),
 			FOREIGN KEY (user) REFERENCES ".User::getTableName()." (id)
 		)";
 		MySQL::query($sql,__FILE__,__LINE__);
	}
	
 	/**
 	Spocita podobnost mezi uzivateli a zapise ji do tabulky.
 	@return void
 	*/
 	public static function updateAll() {
 		//vyprazdni tabulku s podobnosti
 		$sql = "TRUNCATE TABLE ".self::getTableName();
 		MySQL::query($sql,__FILE__,__LINE__);
 		//kolik je uzivatelu
 		$sql = "
 				SELECT 
 				".User::getTableName().".id
 				FROM ".User::getTableName()."
 				WHERE 1";
 		$res = MySQL::query($sql,__FILE__,__LINE__);
 		while($record = mysql_fetch_object($res)){
 			//echo "<br>".$record->id.":";
 			$sql = "
 				SELECT 
 				".User::getTableName().".id
 				FROM ".User::getTableName()."
 				WHERE id > ".$record->id." AND (SELECT COUNT(".Opinion::getTableName().".book) FROM ".Opinion::getTableName()." WHERE user > ".$record->id." GROUP BY book LIMIT 1) > 10";
 			$res2 = MySQL::query($sql,__FILE__,__LINE__);
 			while($record2 = mysql_fetch_object($res2)){
 				if($record->id < $record2->id){
 					//echo "".$record2->id." ";
 					//vyber ctenarskeho deniku jednoho uzivatele
	 				$sql_left = "
	 						SELECT
	 						".Opinion::getTableName().".user,
	 						".Opinion::getTableName().".book,
	 						".Opinion::getTableName().".rating
	 						FROM ".Opinion::getTableName()."
	 						WHERE
	 						".Opinion::getTableName().".user = ".$record->id."";
	 				$res_left = MySQL::query($sql_left,__FILE__,__LINE__);
	 				//vyber ctenarskeho deniu druheho uzivatele
	 				$sql_right = "
	 						SELECT
	 						".Opinion::getTableName().".user,
	 						".Opinion::getTableName().".book,
	 						".Opinion::getTableName().".rating
	 						FROM ".Opinion::getTableName()."
	 						WHERE
	 						".Opinion::getTableName().".user = ".$record2->id."";
	 				$res_right = MySQL::query($sql_right,__FILE__,__LINE__);
	 				//vypocet podobnosti
	 				$nums_left = mysql_num_rows($res_left);
	 				$nums_right = mysql_num_rows($res_right);
	 				if($nums_left > $nums_right){
	 					$nums_all = $nums_left;
	 				} else {
	 					$nums_all = $nums_right;
	 				}
	 				if($nums_left > 10 AND $nums_right > 10){
		 				$pole_sjednoceni = array();
		 				for($k = 0; $k < $nums_all; $k++){
		 					//nacist radky z obou deniku
		 					$record_left = mysql_fetch_object($res_left);
		 					$record_right = mysql_fetch_object($res_right);
		 					//-1 protoze jsem pocital s <0-4>
		 					$pole_sjednoceni[$record_left->book]["delta"] = $record_left->rating - 1;
		 					$pole_sjednoceni[$record_left->book]["pocet"] = 1;
		 					$pole_sjednoceni[$record_right->book]["delta"] = $pole_sjednoceni[$record_right->book]["delta"] - ($record_left->rating -1);
		 					$pole_sjednoceni[$record_right->book]["pocet"]++;
		 					$pole_sjednoceni[$record_right->book]["delta"] = abs($pole_sjednoceni[$record_right->book]["delta"]);
		 				}
		 				$skore = 0;
		 				foreach($pole_sjednoceni as $radek){
		 					if($radek["pocet"] == 2){
								if($radek["delta"] == 0) $prirustek = 10; else
								if($radek["delta"] == 1) $prirustek = 8; else
								if($radek["delta"] == 2) $prirustek = 6; else
								if($radek["delta"] == 3) $prirustek = 4; else
								if($radek["delta"] == 4) $prirustek = 2; else
								$prirustek = 0;
			 						
			 					$skore = $skore + $prirustek; 
		 					}
		 				}
		 				//pro levou stranu
		 				$podobnost1 = ($skore/$nums_left)*10;
		 				//pro pravous tranu
		 				$podobnost2 = ($skore/$nums_right)*10;
		 				//zapis podobnosti
		 				if($podobnost1 != 0)
	 						MySQL::insert(self::getTableName(),array("owner" => $record->id, "user" => $record2->id,"similarity" => $podobnost1));
	 					if($podobnost2 != 0)
	 						MySQL::insert(self::getTableName(),array("owner" => $record2->id, "user" => $record->id,"similarity" => $podobnost2));
	 				}
 				}
	 		}
 		}
 		echo "DONE";
 	}	
	
}
?>