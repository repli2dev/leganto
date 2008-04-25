<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/

/**
* @package reader
* This class is used for work with messages amoung users.
*/
class message extends reader {
	
	/** @var string Name of table in database. */
 	public $table; 

	/**
	* Constructor, here is called reader's constructor and message::$table is inicializated.
	* @return void
	*/
 	public function __construct() {
  		parent::__construct();
  		$this->table = $this->sqlPrefix."message";
 	}	

	public function countNotRead() {
		$owner = $this->owner->id;
		$sql = "SELECT COUNT(id) AS notRead FROM $this->table AS notRead WHERE usTo = $owner AND isRead = 1";
		$res = mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
		$record = mysql_fetch_object($res);
		return $record->notRead;
	} 	

	public function destroy($id) {
		$help = FALSE;
		try {
			if (!$id) throw new error("");
			$sql = "SELECT usTo, usFrom,toDestroy,fromDestroy FROM $this->table WHERE id=$id";
			$res = mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
			$record = mysql_fetch_object($res);
			if ($this->owner->id == $record->usTo) {
				if ($record->fromDestroy == 2) {
					$sql = "DELETE FROM $this->table WHERE id = $id";
				}
				else {
					$sql = "UPDATE $this->table SET toDestroy = 2 WHERE id = $id";
				}
				mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
				$help = TRUE;
			}
			else if ($this->owner->id == $record->usFrom) {
				if ($record->toDestroy == 2) {
					$sql = "DELETE FROM $this->table WHERE id = $id";
				}
				else {
					$sql = "UPDATE $this->table SET fromDestroy = 2 WHERE id = $id";
				}
				mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
				$help = TRUE;
			}
			return $pom; 
		}
		catch (errror $e) {
			$e->scream();
			return FALSE;
		}
	}
 	
	/**
	* Create table in database.
	* @return void
	*/
 	public function install() {
  		$user = new user;
  		$sql = "CREATE TABLE $this->table (
   		id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
   		usTo INT(25) NOT NULL,
   		usFrom INT(25) NOT NULL,
   		content TEXT NOT NULL,
   		isRead ENUM('1','2') NOT NULL,
   		toDestroy ENUM('1','2') NOT NULL,
   		fromDestroy ENUM('1','2') NOT NULL,
   		date DATETIME default '0000-00-00 00:00:00',
   		FOREIGN KEY (usTo) REFERENCES $user->table (id),
   		FOREIGN KEY (usFrom) REFERENCES $user->table (id)
  		)";
  		unset($user);
  		mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql); 
 	}
 	
	public function readRecieved() {
		$owner = $this->owner->id;
		$sql = "UPDATE $this->table SET isRead = 2 WHERE usTo = $owner";
		mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
		$user = new user;
		$sql = "
			SELECT
				$this->table.id AS mesID,
				$this->table.date,
				$this->table.content,
				$this->table.usFrom AS userIDFrom,
				$user->table.name AS userNameFrom,
				$this->table.usTo AS userIDTo,
				(SELECT $user->table.name FROM $this->table LEFT JOIN $user->table ON $this->table.usTo = $user->table.id WHERE $this->table.id = mesID) AS userNameTo
			FROM
				$this->table
			LEFT JOIN $user->table ON $this->table.usFrom = $user->table.id
			WHERE
				$this->table.usTo = $owner
			AND
				$this->table.toDestroy = 1
			GROUP BY $this->table.id
			ORDER BY $this->table.date DESC
		";
		unset($user);
		$res = mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
		return $res;
	} 
 	
 	public function readSent() {
		$owner = $this->owner->id;
		$user = new user;
		$sql = "
			SELECT
				$this->table.id AS mesID,
				$this->table.date,
				$this->table.content,
				$this->table.usFrom AS userIDFrom,
				$user->table.name AS userNameFrom,
				$this->table.usTo AS userIDTo,
				(SELECT $user->table.name FROM $this->table LEFT JOIN $user->table ON $this->table.usTo = $user->table.id WHERE $this->table.id = mesID) AS userNameTo
			FROM
				$this->table
			LEFT JOIN $user->table ON $this->table.usFrom = $user->table.id
			WHERE
				$this->table.usFrom = $owner
			AND
				$this->table.fromDestroy = 1
			GROUP BY $this->table.id
			ORDER BY $this->table.date DESC
		";
		unset($user);
		$res = mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
		return $res;
 	}
 	
 	/**
 	* Send message to user.
 	* @param int User ID.
 	* @param string Content of the message.
 	* @return void.
 	*/
 	public function send($userToName,$content) {
		try {
			$user = new user; 		
 			$sql = "SELECT id FROM $user->table WHERE name = '$userToName'";
 			unset($user);
 			$res = mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
 			$record = mysql_fetch_object($res);
 			if (!$record->id) {
 				$lng = new language;
 				throw new error($lng->noUserForMessage);
 			}
 			if ($record->id == $this->owner->id) {
 				$lng = new language;
 				throw new error($lng->messageToOwner);
 			}
 			$userTo = $record->id;
 			$owner = $this->owner->id;
			$sql = "INSERT INTO $this->table VALUES(0,$userTo,$owner,'$content',1,1,1,now())";
			mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
		}
		catch(error $e) {
			$e->scream();
		}
 	}
 	
}