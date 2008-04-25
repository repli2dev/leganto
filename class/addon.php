<?php/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/

/**
* @package reader
* This class is used for work with addon.
*/
class addon extends reader {
 	
 	/** @var string Name of table in database */
 	public $table; 

	/**
	* Constructor, here is called reader's constructor and advertisement.table is inicializated.
	* @return void
	*/
 	public function __construct() {
  		parent::__construct();
  		$this->table = $this->sqlPrefix."addon";
 	}
 
	public function change($id,$content,$bookID) {
		$sql = "UPDATE $this->table SET content = '$content', book = $bookID WHERE id = $id";
		mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
	} 
 
 	/**
 	* Create addon.
 	* @var string Content of advert
 	* @var int ID of book
 	* @return void
 	*/
 	public function create($bookID,$content) {
  		$sql = "INSERT INTO $this->table VALUES(0,'$content',$bookID)";
  		mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
 	} 
 
	 public function destroy($id) {
	 	$sql = "DELETE FROM $this->table WHERE id = $id";
	 	mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
	 }
 
 	/**
 	* Return addon from the book.
 	* @var int ID of book
 	* @return advertisement_mysql_query
 	*/
 	public function getByBook($bookID) {
  		$sql = "SELECT * FROM $this->table WHERE book = $bookID";
  		$res = mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
  		return $res;
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
   		content TEXT NOT NULL,
   		book INT(25) NOT NULL,
   		FOREIGN KEY (book) REFERENCES $book->table (id)
  		)";
 	 	unset($book);
  		mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
 	}
}