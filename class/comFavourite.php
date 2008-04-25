<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/

/**
* @package reader
* This class is used for looking after favourite discussions by books.
*/
class comFavourite extends reader {
	
	/** @var string Name of table in database. */
	public $table;
 
 	/**
 	* Constructor, here is called reader's constructor and comFavourite::$table is inicializated.
 	* @return void
 	*/
 	public function __construct() {
  		parent::__construct();
  		$this->table = $this->sqlPrefix."comFavourite";
 	}

	/**
	* Make discussion by book favourite.
	* @param int Book ID.
	* @return void
	*/
 	public function create($bookID) {
  		$owner = $this->owner->id;
  		$sql = "INSERT INTO $this->table VALUES(0,$owner,$bookID)";
  		mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
 	}

	/**
	* Unmake discussion by book favourite.
	* @param int Book ID.
	* @return void
	*/
 	public function destroy($bookID) {
  		$owner = $this->owner->id;
  		$sql = "DELETE FROM $this->table WHERE book = $bookID AND user = $owner";
  		mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
 	}

	/**
	* Get favourite discussions by book from user.
	* @param int User's ID.
	* @return comFavourite_mysql_query
	*/
 	public function getByUser($usID) {
  		$opinion = new opinion;
  		$book = new book;
  		$sql = "
   		SELECT $this->table.id FROM $book->table
   		LEFT JOIN $opinion->table ON $book->table.id = $opinion->table.book
   		LEFT JOIN $this->table ON $book->table.id = $this->table.book
   		WHERE $opinion->table.user = $usID OR $this->table.user
   		GROUP BY $this->table.id 
  		";
  		unset($book);
  		unset($opinion);
  		$res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  		return $res;
 	}
 
 	/**
 	* Create table in database.
 	* @return void
 	*/
 	public function install() {
  		$user = new user;
  		$book = new book;
  		$sql = "CREATE TABLE $this->table (
   		id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
   		user INT(25),
   		book INT(25),
   		FOREIGN KEY (user) REFERENCES $user->table (id),
   		FOREIGN KEY (book) REFERENCES $book->table (id)
  		)";
  		unset($user);
  		unset($book);
  		mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
 	}
 	
 	/**
 	* Return TRUE, if discussion by book is favourite discussion of logged user, else return FALSE.
 	* @param int Book ID.
 	* @return boolean
 	*/
 	public function isFavourite($bookID) {
  		$owner = $this->owner->id;
  		$sql = "SELECT id FROM $this->table WHERE user = $owner AND book = $bookID";
  		$res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  		if (mysql_num_rows($res) > 0) return TRUE;
  		else return FALSE;
 	}
}
?>