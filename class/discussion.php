<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/

/**
* @package reader
* This class is used for work with discussion.
*/
class discussion extends reader {
	public $limit = 50;
 	public $table; 

 	public function __construct() {
  		parent::__construct();
  		$this->table = $this->sqlPrefix."discussion";
 	}
 
 	public function create($text) {
  		try { 
   		$lng = new language;
   		if (!$this->owner-id) throw new error($lng->accessDenied);
   		if (!$text) throw new error($lng->discussWithoutText);
			$user = $this->owner->id;   
   		$sql = "INSERT INTO $this->table VALUES(
    			0,
    			$user,
    			'$text',
    			now()
   		)";
  			mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
  		}
  		catch (error $exception) {
   		$exception->scream();
  		}    
 	}

 	public function destroyByID($disID) {
  		try {
   		$lng = new language;
   		$user = new user;
   		if (($this->owner->level < $user->levelModerator) and ($this->owner->id != $this->getOwner($comID))) throw new error($lng->accessDenied);
   		$sql = "DELETE FROM $this->table WHERE id = $disID";
   		mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
  		}
  		catch (error $exception) {
   		$exception->scream();
  		}    
 	}

 	public function install() {
  		$user	= new user;
  		$sql = "CREATE TABLE $this->table (
   		id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
   		user INT(25) NOT NULL,   
   		text TEXT NOT NULL,
   		date DATETIME default '0000-00-00 00:00:00',
   		FOREIGN KEY (user) REFERENCES $user->table (id)
  		)";
  		mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
 	}
 
 	public function read($page) {
  		$limit = $this->limit*page;
  		$user = new user;
  		$sql = "
   		SELECT
    			$this->table.id,
    			$this->table.text,
    			$this->table.date,
    			$user->table.id AS userID,
    			$user->table.name AS userName,
    			$this->limit AS pageLimit,
    			(SELECT COUNT($this->table.id) FROM $this->table) AS pageHelp
   		FROM
    			$this->table
   		LEFT JOIN $user->table ON $this->table.user = $user->table.id
   		GROUP BY $this->table.id
   		ORDER BY $this->table.date DESC
   		LIMIT $limit,$this->limit  
  		";
  		$res = mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
  		return $res;
 	}
}
?>