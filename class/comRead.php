<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/

/**
* @package reader
* This class is used for work with comments what were by users read.
*/
class comRead extends reader {
	
	/** @var string Name of table in database */	
	public $table;
 
 	/**
 	* Constructor, here is called reader's constructor and comRead::$table is inicializated.
 	* @return void
 	*/
 	public function __construct() {
  		parent::__construct();
  		$this->table = $this->sqlPrefix."comRead";
 	}

	/**
	* Make comments by logged user read.
	* @param int Book ID.
	* @return void.
	*/ 
 	public function actual($bookID) {
  		if ($usID = $this->owner->id) { 
   		$opinion = new opinion;
   		$book = new book;
   		$comFavourite = new comFavourite;  
   		$sql = "
    			SELECT $comFavourite->table.id FROM $book->table
    			LEFT JOIN $opinion->table ON $book->table.id = $opinion->table.book
    			LEFT JOIN $comFavourite->table ON $book->table.id = $comFavourite->table.book
    			WHERE
     				($opinion->table.user = $usID AND $opinion->table.book = $bookID)
     			OR
     				($comFavourite->table.user AND $comFavourite->table.book = $bookID)
    			GROUP BY $comFavourite->table.id
   		";
   		unset($book);
   		unset($opinion);
   		unset($comFavourite);
   		$res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
   		if (mysql_num_rows($res) > 0) {
    			$comment = new comment;
    			$sql = "
	  				SELECT $comment->table.id
	  				FROM $comment->table
	  				WHERE $comment->table.id NOT IN ((SELECT $this->table.comment FROM $this->table WHERE $this->table.user = $usID))
	  				AND $comment->table.book = $bookID    
    			";	  
    			$res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
    			while ($comment = mysql_fetch_object($res)) {
	  				$sql = "INSERT INTO $this->table VALUES(0,$usID,$comment->id)";
	  				mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
	 			} 
   		}
  		}
 	} 

	/**
	* Return comments what were by logged user not read.
	* @param int User's ID.
	* @return comRead_mysql_query
	*/
 	public function getNotRead($usID) {
  		$comment = new comment;
  		$book = new book;  
  		$opinion = new opinion;
  		$comFavourite = new comFavourite;
  		$sql = "
   		SELECT
    				COUNT(DISTINCT $comment->table.id) AS comNumber,
    			$book->table.id AS bookID,
    			$book->table.title AS bookTitle 
   		FROM $comment->table
		   LEFT JOIN $this->table ON $comment->table.id = $this->table.comment
   		LEFT JOIN $book->table ON $comment->table.book = $book->table.id
   		LEFT JOIN $opinion->table ON $comment->table.book = $opinion->table.book
   		LEFT JOIN $comFavourite->table ON $book->table.id = $comFavourite->table.book		
   		WHERE
   			$comment->table.id NOT IN ((SELECT $this->table.comment FROM $this->table WHERE $this->table.user = $usID))
   		AND
   			($opinion->table.user = $usID OR $comFavourite->table.user = $usID)   
   		GROUP BY bookID
   	";
  		unset($comment);
  		unset($book);
  		$res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  		return $res;
 	} 

	/**
	* Create table in database.
	* @return void.
	*/
 	public function install() {
  		$comment = new comment;
  		$sql = "CREATE TABLE $this->table (
   		id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
   		user INT(25),
   		comment INT(25),
   		FOREIGN KEY (user) REFERENCES $user->table (id),
   		FOREIGN KEY (comment) REFERENCES $comment->table (id)
  		)";
  		unset($comment);
  		mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
 	}
}
?>