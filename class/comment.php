<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/

/**
* @package reader
* This class is used for work with discussions by books (comments).
*/
class comment extends reader {

	/** @var int Number of showed items */
 	public $limit = 100;	
	/** @var string Name of table in database */	
	public $table;

 	/**
 	* Constructor, here is called reader's constructor and comment::$table is inicializated.
 	* @return void
 	*/
 	public function __construct() {
  		parent::__construct();
  		$this->table = $this->sqlPrefix."comment";
 	}

	/**
	* Create table in database.
	* @return void
	*/ 
 	public function install() {
  		$book = new book;
  		$user	= new user;
  		$sql = "CREATE TABLE $this->table (
   		id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
   		book INT(25) NOT NULL,
   		user INT(25) NOT NULL,   
   		text TEXT NOT NULL,
   		date DATETIME default '0000-00-00 00:00:00',
   		rating INT(25) default 0,
   		FOREIGN KEY (book) REFERENCES $book->table (id),
   		FOREIGN KEY (user) REFERENCES $user->table (id)
  		)";
  		mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
 	}
 
 	/**
 	* Destroy table in database.
 	* @return void
 	*/
 	public function uninstall() {
  		$sql = "DROP TABLE $this->table";
  		mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
 	}
  	
  	/**
  	* Create comment (discussion item).
  	* @param int Book ID.
  	* @param string Content of comment.
  	* @return void
  	*/
 	public function create($book,$text) {
  		try { 
   		$lng = new language;
   		if (!$this->owner-id) throw new error($lng->accessDenied);
   		if (!$text) throw new error($lng->commentWithoutText);
			$user = $this->owner->id;   
   		$sql = "INSERT INTO $this->table VALUES(
    			0,
    			$book,
    			$user,
    			'$text',
    			now(),
    			0
   		)";
  			mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
  		}
  		catch (error $exception) {
   		$exception->scream();
  		}    
 	}
 
 	/**
 	* Destroy comment.
 	* @param int Comment ID.
 	* @return void
 	*/
 	public function destroyByID($comID) {
  		try {
   		$lng = new language;
   		$user = new user;
   		if (($this->owner->level < $user->levelModerator) and ($this->owner->id != $this->getOwner($comID))) throw new error($lng->accessDenied);
   		$sql = "DELETE FROM $this->table WHERE id = $comID";
   		mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
  		}
  		catch (error $exception) {
   		$exception->scream();
  		}    
 	}

	/**
	* Get owner of comment.
	* @param int Comment ID.
	* @return int
	*/

	private function getOwner($comID) {
		$sql = "SELECT user FROM $this->table WHERE id = $comID";
		$res = mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
		$record = mysql_fetch_object($res);
		return $record->user;
	}	
	
	/**
	* Get last n commented books
	* @param int Count of books.
	* @return commentRead_mysql_query
	*/	
	public function lastCommentedBooks($n) {
		$book = new book; 
  		$sql = "
   		SELECT
				COUNT(DISTINCT($this->table.id)) AS comNumber,
				$book->table.id AS bookID,
				$book->table.title AS bookTitle,
				MAX($this->table.date) AS comDate
   		FROM $this->table
   		LEFT JOIN $book->table ON $this->table.book = $book->table.id
   		GROUP BY bookID
   		ORDER BY comDate DESC
   		LIMIT 0,$n
  		";
  		unset($book);
  		$res = mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
  		return $res;	
	}
	
	/**
	* Read commnets of book.
	* @param int Book ID.
	* @return void
	*/
 	public function read($book) {
  		$user = new user;  
  		$sql = "
   		SELECT
    			$this->table.id,
    			$this->table.text,
    			$this->table.date,
    			$this->table.rating,
    			$user->table.id AS userID,
    			$user->table.name AS userName,
    			$user->table.email AS userEmail   
   		FROM $this->table
   		INNER JOIN $user->table ON $this->table.user = $user->table.id
   		WHERE $this->table.book = $book
   		GROUP BY $this->table.id
   		ORDER BY $this->table.date
  		";
  		unset($user);
  		$res = mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
  		return $res;
 	}
}
?>
