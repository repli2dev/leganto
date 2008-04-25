<?php/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/

/**
* @package reader
* This class is used for work with advertisement.
*/
class advertisement extends reader {
 	
 	/** @var string Name of table in database */
 	public $table; 

	/**
	* Constructor, here is called reader's constructor and advertisement.table is inicializated.
	* @return void
	*/
 	public function __construct() {
  		parent::__construct();
  		$this->table = $this->sqlPrefix."advertisement";
 	}
 
	/**
	* Clean table in database from old advert.
	* @return void
	*/
 	public function clean() {
  		$sql = "DELETE FROM $this->table WHERE endDate < now()";
  		mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
 	} 
 
 	/**
 	* Create advert.
 	* @var string Content of advert
 	* @var int ID of book
 	* @var date End of advert
 	* @return void
 	*/
 	public function create($content,$bookID,$endDate) {
  		$sql = "INSERT INTO $this->table VALUES(0,'$content',$bookID,'$endDate')";
  		mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
 	} 
 	/**
 	* Return advert from the book.
 	* @var int ID of book
 	* @return advertisement_mysql_query
 	*/
 	public function getByBook($bookID) {
  		$sql = "SELECT * FROM $this->table WHERE book = $bookID";
  		$res = mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
  		return $res;
 	}  
 
	/**
	* Create table in database.
	* @return void
	*/
 
 	public function install() {
  		$book = new book;
  		$sql = "CREATE TABLE $this->table (
   		id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
   		content TEXT NOT NULL,
   		book INT(25) NOT NULL,
   		endDate DATE default '0000-00-00',
   		FOREIGN KEY (book) REFERENCES $book->table (id)
  		)";
 	 	unset($book);
  		mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
 	}
}