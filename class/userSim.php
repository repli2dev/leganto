<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/

/**
* @package reader
* This class is used for work similarity of users.
*/
class userSim extends reader {

	/** @var string Name of table in database. */
	public $table;
	
	/**
	* Constructor, here is called reader's constructor and userSim::$table is inicializated.
	* @return void
	*/
 	public function __construct() {
  		parent::__construct();
  		$this->table = $this->sqlPrefix."userSim";
 	}
 	
	/**
	* Get similarity by user's ID.
	* @param int User ID.
	* @return int Similarity
	*/ 	
 	
	public function get($usID) {
		$owner = $this->owner->id;
		$sql = "SELECT similarity FROM $this->table WHERE owner = $owner AND user = $usID";
		$res = mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);		
		$record = mysql_fetch_object($res);
		return $record->similarity;		
	} 	
 	
 	/**
 	Create table in database.
 	@return void
 	*/
 	public function install() {
 		$user = new user;
 		$sql = "CREATE TABLE $this->table (
 			id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
 			owner INT(25),
 			user INT(25),
 			similarity INT(3),
 			FOREIGN KEY (owner) REFERENCES $user->table (id),
 			FOREIGN KEY (user) REFERENCES $user->table (id)
 		)";
 		mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
 	}
 	
 	/**
 	Count similarity of all users and write it down.
 	@return void
 	*/
 	public function updateAll() {
 		$sql = "TRUNCATE TABLE $this->table";
 		mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
 		$opinion = new opinion;
 		$tagRef = new tagReference;
 		$user = new user;
 		$sql = "SELECT id AS owner FROM $user->table";
 		$res = mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
 		while($record = mysql_fetch_object($res)) {
 			$owner = $record->owner;
			$sql = "
				SELECT
					$user->table.id,
    				ROUND(
     					(
      					(
       						SELECT COUNT($tagRef->table.tag)*$opinion->table.rating AS help FROM $opinion->table
       						LEFT JOIN $tagRef->table ON $opinion->table.book = $tagRef->table.book
       						WHERE
       							$tagRef->table.tag IN
       								(
       									SELECT $tagRef->table.tag
       									FROM $opinion->table
       									LEFT JOIN $tagRef->table ON $opinion->table.book = $tagRef->table.book
       									WHERE $opinion->table.user = $owner
       									GROUP BY $tagRef->table.tag
       								)
       						AND
       							$opinion->table.user = $user->table.id
      					)
      					/
      					(
      						SELECT COUNT($tagRef->table.tag)*$opinion->table.rating FROM $opinion->table
      						LEFT JOIN $tagRef->table ON $opinion->table.book = $tagRef->table.book
        						WHERE $opinion->table.user = $owner OR $opinion->table.user = $user->table.id)
     						)*100
    					) AS similarity
    			FROM $user->table
    			WHERE $user->table.id != $owner							
			";
			$res2 = mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
			while ($record2 = mysql_fetch_object($res2)) {
				if (!$record2->similarity) $record2->similarity = 0;	
				$sql = "INSERT INTO $this->table VALUES(0,$owner,$record2->id,$record2->similarity)";
				$res3 = mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
			}
 		}
 	}
}
?>