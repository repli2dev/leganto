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
class Writing extends MySQLTableWriting {
        
        /**
        * @var string Nazev tabulky.
        */
        const TABLE_NAME = "writing";

        /**
        * @var mixed Nazvy poli tabulku v databazi, ktera se vzdy musi vyplnit. 
        */
        public static $importantColumns = array("id","title");

        /**
        * @var int Pocet vracenych polozek, je-li vracen seznam.
        */
        const LIST_LIMIT = 20;

		/**
        * @var int Pocet vracenych polozek, je-li vracen do TOP10.
        */
        const LIMIT = 10;

		/**
		 * Přečte jeden zápisek
		 * @param int id zapisku
		 * @return array pole vysledku
		 */
		public static function read($id){
			$sql = "
					SELECT
					" . self::getCommonItems() ."
					FROM ".self::getTableName()."
					" . self::getCommonJoins() . "
					WHERE ".self::getTableName().".id = ".$id."
				   ";
			return Mysql::query($sql,__FILE__,__LINE__);
		}

        /**
        * Vytvori zapisek v databazi.
        * @param string titulek
		* @param string text
		* @param string link
        * @return int ID zapisku
        */
        public static function create($title, $text, $link, $tags) {
				$owner = Page::session("login");
				try {
					if(empty($owner->id)) throw new Error(Lng::ACCESS_DENIED);
					if (empty($tags)) {
						throw new Error(Lng::WITHOUT_ARGUMENT . "tags.");
					}
					$w = parent::create(array("title" => $title, "text" => $text, "link" => $link, "user" => $owner->id, "date" => "now()"));
					//$id = mysql_insert_id();
					TagReference::create($tags,$w->id,"writing");
					return $w;
					
				}
				catch (Error $exception) {
                        $exception->scream();
                }
        }

		/**
        * Upravi zapisek v databazi.
        * @param string titulek
		* @param string text
		* @param string link
		* @param int id
        * @return int ID zapisku
        */
        public static function change($title, $text, $link, $id) {
				$owner = Page::session("login");
				try {
					if(!($op = Writing::isMine($id)) AND !($owner->level > User::LEVEL_COMMON)) throw new Error(Lng::ACCESS_DENIED);
					$id = parent::change($id,array("title" => $title, "text" => $text, "link" => $link));
					//$id = mysql_insert_id();
					return $id;
				}
				catch (Error $exception) {
                        $exception->scream();
                }
        }
		
        /**
         *			Odstrani polozku z databaze.
         *
         * @param 	int		ID polozky
         * @return 	boolean
         */
        public static function destroy($id) {
        	try {
        		$owner = Page::session("login");
        		$w = self::getInfo($id);
        		if (($owner->level <= User::LEVEL_COMMON) && ($owner->id != $w->userID)) {
        			throw new Error(Lng::ACCESS_DENIED);
        		}
	        	Discussion::destroyByFollow($id,"writing");
	        	TagReference::destroy($id,"writing");
	        	parent::destroy($id);
	        	return TRUE;
        	}
        	catch(Error $e) {
        		$e->scream();
        	}
        }
        
		/**
		* Vrati SQL dotaz typu SELECT.
		* @param mixed Podminky SQL dotazu.
		* @param strign Polozka podle, ktere se vysledek seradi.
		* @param int Pocatecni hodnota limitu.
		* @param int Pocet vracenych polozek (LIMIT).
		* @return string SQL dotaz.
		*/
		public static function getCommonQuery($cond,$order,$limit1,$limit2) {
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
		 * Vrati pozadovane sloupce
		 * @return array
		 */
		protected static function getCommonItems() {
			return "
				".self::getTableName().".id AS id,
				".self::getTableName().".title AS title,
				".self::getTableName().".text AS text,
				".self::getTableName().".user AS user,
				".self::getTableName().".link AS link,
				".self::getTableName().".date AS date,
				".User::getTableName().".id AS userId,
				".User::getTableName().".name AS userName
			";
		}

		/**
	* Vrati bezne pripojovani tabulek v SQL dotazech.
	* @return string
	*/
		protected static function getCommonJoins() {
			return "
				LEFT JOIN ".User::getTableName()." ON ".self::getTableName().".user = ".User::getTableName().".id
			";
		}

        /**
		 * Vrati objekt s řádky z tabulky
		 */
		public static function makeCommonQuery(){
			$sql = Writing::getCommonQuery(array("1"),"date DESC",0,self::LIST_LIMIT);
			return MySQL::query($sql,__FILE__,__LINE__);
		}

        /**
        * Vytvori tabulku v databazi.
        * @return void
        */
        public static function install() {
                $sql = "CREATE TABLE ".self::getTableName()." (
						id INT UNSIGNED NOT NULL AUTO_INCREMENT ,
						user INT UNSIGNED NOT NULL ,
						title TEXT NOT NULL ,
						text TEXT NOT NULL ,
						link TEXT NOT NULL ,
						date DATETIME NOT NULL ,
						PRIMARY KEY ( `id` )
				)";
                MySQL::query($sql,__FILE__,__LINE__);
        }

		 /**
        * Zjisti, jestli nalezi zapisek prihlasenemu uzivateli
        * @param int ID zapisku
        * @return boolean
        */
        public static function isMine($entryID) {
                $owner = Page::session("login");
                $sql = "SELECT id FROM ".self::getTableName()." WHERE id = $entryID AND user = ".$owner->id;
                $res = MySQL::query($sql,__FILE__,__LINE__);
                if (mysql_num_rows($res) > 0) {
                	$result = mysql_fetch_object($res);
                	return $result->id;
                }
                else return FALSE;
        }
		/**
		 * Vrati nejnovejsi zapisky.
		 * @return array TOP10 nejnovejsich zapisku
		 */
		public static function getLast() {
			$sql = "
				SELECT
					".self::getCommonItems()."
				FROM ".self::getTableName()."
				".self::getCommonJoins()."
				WHERE 1
				ORDER BY date DESC
				LIMIT 0,".self::LIMIT."
			";

			return MySQL::query($sql,__FILE__,__LINE__);
		}

		/**
		 * Vrati nejnovejsi zapisky, daneho uzivatele
		 * @return array TOP10 nejnovejsich zapisku daneho uzivatele
		 */
		public static function last($user,$id = NULL) {
			if(!empty($id)){
				$cond = "AND id != ".$id;
			}
			$sql = "
				SELECT
					id,
					title,
					link
				FROM ".self::getTableName()."
				WHERE user = ".$user."
				$cond
				ORDER BY date DESC
				LIMIT 0,".self::LIMIT."
			";
			return MySQL::query($sql,__FILE__,__LINE__);
		}

		/**
		 * Vrati id uzivatele, kteremu patri zapisek s ID
		 * @param int ID prispevku
		 * @return int ID uzivatele
		 */
		public function entryOwner($id){
			$sql = "
				SELECT
					user
				FROM ".self::getTableName()."
				WHERE id = ".$id."
			";
			$res = MySQL::query($sql,__FILE__,__LINE__);
			$row = mysql_fetch_object($res);
			return $row->user;
		}

		/**
		 * Vráti všechny zápisky daného uživatele
		 * @param int ID uzivatele
		 * @return array pole s vysledky
		 */
		public function getAllUser($user){
			$sql = Writing::getCommonQuery(array(self::getTableName().".user = ".$user.""),"date DESC",0,self::LIST_LIMIT);
			return MySQL::query($sql,__FILE__,__LINE__);
		}


}
