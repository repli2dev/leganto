<?php/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/

/**
* @package reader
* This class is used for work with events.
*/
class addon extends reader {
 	
 	/** @var string Name of table in database */
 	public $table; 

	/**
	* Constructor, here is called reader's constructor and event.table is inicializated.
	* @return void
	*/
 	public function __construct() {
  		parent::__construct();
  		$this->table = $this->sqlPrefix."addon";
 	}
 
	public function change($id,$content,$endDate) {
		try {
			$sql = "SELECT user FROM $this->table WHERE id = $id";
			$res = mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
			$record = mysql_fetch_object($res);
			if ($record->user != $this->owner->id) {
				$lng = new language;
				throw new error($lng->accessDenied);
			}
			$sql = "UPDATE $this->table SET content = '$content', endDate = '$endDate' WHERE id = $id";
			mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
		}
		catch(error $e) {
		}
	} 
 
 	/**
 	* Create event.
 	* @var string Content of event
 	* @var int ID of book
 	* @var date
 	* @return void
 	*/
 	public function create($bookID,$content,$endDate) {
 		$endDate = $this->dateShakeShort($endDate);
  		$sql = "INSERT INTO $this->table VALUES(0,'$content',$bookID)";
  		mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
 	}  
 
	public function destroy($id) {
		$sql = "DELETE FROM $this->table WHERE id = $id";
		mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
	 }

	public function getByID($id) {
		$sql = "SELECT * FROM $this->table WHERE id = $id";
		$res = mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
		$record = mysql_fetch_object($res);
		return $record;
	}
 
	/**
	* Create table in database.
	* @return void
	*/
 
 	public function install() {
  		$book = new book;
  		$sql = "CREATE TABLE $this->table (
   		id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
   		user INT(25) NOT NULL
   		content TEXT NOT NULL,
   		FOREIGN KEY (user) REFERENCES $user->table (id)
  		)";
 	 	unset($book);
  		mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
 	}
}