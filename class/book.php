<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/

/**
* @package reader
* This class is used for work with books.
*/
class book extends reader {
	
	/** @var int Number of returned items. */
	public $limit = 50;
	
	/** @var string Name of table in database. */
 	public $table; 

	/**
	* Constructor, here is called reader's constructor and book::$table is inicializated.
	* @return void
	*/
 	public function __construct() {
  		parent::__construct();
  		$this->table = $this->sqlPrefix."book";
 	}

	/**
	* Return favourite books of the user.
	* @param int ID of user.
	* @param int Number of returned items.
	* @return book_mysql_query
	*/
 	public function byFavourite($usID,$limit = 10) {
  		$recommend = new recommend;
  		$opinion = new opinion;
  		$writer = new writer;
  		$comment = new comment;
  		$sql = "
   		SELECT
    			$this->table.id,
    			$this->table.title AS title,
    			$this->table.asciiTitle,
    			$this->table.date AS date,
    			$writer->table.id AS writerID,
    			$writer->table.name AS writerName,
    			COUNT($comment->table.id) AS commentCount,
    			ROUND(AVG($opinion->table.rating)) AS rating,
    			COUNT($opinion->table.id) AS readed,
    			(SELECT COUNT($this->table.id) FROM $this->table
     				LEFT JOIN $opinion->table ON $this->table.id = $opinion->table.book
     				WHERE $opinion->table.user = $usID
         	) AS pageHelp,
    			$this->limit AS pageLimit,
    			$opinion->table.id AS opinionID,
    			$opinion->table.user AS opinionUser
   		FROM $this->table
   		LEFT JOIN $writer->table ON $this->table.writer = $writer->table.id
   		LEFT JOIN $comment->table ON $this->table.id = $comment->table.book
   		LEFT JOIN $opinion->table ON $this->table.id = $opinion->table.book
   		INNER JOIN $recommend->table ON $opinion->table.user = $recommend->table.recommend
   		WHERE $recommend->table.user = $usID
   		GROUP BY $this->table.id
   		ORDER BY $opinion->table.date DESC
   		LIMIT 0,$limit
  		"; 
  		$res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  		return $res;  
 	}

	/**
	* Return books of the user.
	* @param int ID of user.
	* @param string Name of item, what returned items are ordered by.
	* @param int Number of page.
	* @return book_mysql_query
	*/
 	public function byUser($usID,$order,$page) {
  		switch ($order) {
   		case "rating": $order .= " DESC"; break;
   		case "readed": $order .=  " DESC"; break;
   		case "": $order = "title";
  		}
  		$limit = $page*$this->limit;
  		$writer = new writer;
  		$comment = new comment;
  		$opinion = new opinion;
  		$sql = "
   		SELECT
    			$this->table.id,
    			$this->table.title AS title,
    			$this->table.asciiTitle,
    			$this->table.date AS date,
    			$writer->table.id AS writerID,
    			$writer->table.name AS writerName,
    			COUNT($comment->table.id) AS commentCount,
    			ROUND(AVG($opinion->table.rating)) AS rating,
    			COUNT($opinion->table.id) AS readed,
    			(SELECT COUNT($this->table.id) FROM $this->table
     				LEFT JOIN $opinion->table ON $this->table.id = $opinion->table.book
     				WHERE $opinion->table.user = $usID
         	) AS pageHelp,
    			$this->limit AS pageLimit,
    			$opinion->table.id AS opinionID
   		FROM $this->table
   		LEFT JOIN $writer->table ON $this->table.writer = $writer->table.id
   		LEFT JOIN $comment->table ON $this->table.id = $comment->table.book
   		LEFT JOIN $opinion->table ON $this->table.id = $opinion->table.book
   		WHERE $opinion->table.user = $usID
   		GROUP BY $this->table.id
   		ORDER BY $order
   		LIMIT $limit,$this->limit
  		";
  		unset($writer);
  		unset($opinion);
  		unset($comment);
  		$res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  		return $res;
 	}

	/**
	* Change info of the book.
	* @param int ID of book.
	* @param string New title of book.
	* @param string New name of writer.
	* @return void
	*/
	public function changeAll($bookID,$bookTitle,$writerName) {
		try {
  			$asciiBookTitle = $this->utf2ascii($bookTitle);
  			$sql = "SELECT id FROM $this->table WHERE title = '$bookTitle'";
  			$res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  			$record = mysql_fetch_object($res);
  			if (($record->id) and ($record->id != $bookID)) {
  				$lng = new language;
  				throw new error($lng->bookExists);
  			}
  			$sql = "UPDATE $this->table SET title = '$bookTitle',asciiTitle = '$asciiBookTitle' WHERE id = $bookID";
  			mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  			$writerID = $this->getInfo($bookID);
  			$writer = new writer;
  			$writer->change($writerID->writerID,$writerName);
  			unset($writer);
  		}  			
  		catch(error $e) {
  			$e->scream();
  			$this->connect($record->id,$bookID);
  		}
 	}

	/**
	* Connect two books.
	* @param int ID of book, which is destroyed.
	* @param int ID of book, which is not destroyed.
	* @return boolean
	*/
 	public function connect($first,$second) {
  		$comment = new comment;
  		$sql = "
   		UPDATE $comment->table
   		SET book = $first
   		WHERE book = $second
  		"; 
  		mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  		unset($comment);
  		$opinion = new opinion;
  		$sql = "SELECT user FROM $opinion->table WHERE book = $first";
  		$res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
		while ($record = mysql_fetch_object($res)) {
			if ($us) $us .= ",";
			$us .= $record->user;
		}  	  		
  		$sql = "
   		UPDATE $opinion->table
   		SET book = $first
   		WHERE book = $second
   		AND user NOT IN ($us) 
  		";
  		mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  		$sql = "DELETE FROM $opinion->table WHERE book = $second";
  		mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  		unset($opinion);
  		$tagReference = new tagReference;
  		$sql = "SELECT tag FROM $tagReference->table WHERE book = $first"; 
		$res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
		while ($record = mysql_fetch_object($res)) {
			if ($tag) $tag .= ",";
			$tag .= $record->tag;
		}  		
  		$sql = "
			UPDATE $tagReference->table
			SET book = $first
			WHERE tag NOT IN ($tag)
			AND book = $second
  		";
  		mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  		unset($tagReference);
  		$sql = "DELETE FROM $this->table WHERE id = $second";
  		mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  		return TRUE;
 	}

	/**
	* Create book and it's writer, if they don't exist.
	* @param string Title of book.
	* @param string Name of writer.
	* @return book_record
	*/
 	public function create($bookTitle,$writerName) {
  		$asciiBookTitle = $this->utf2ascii(strToLower($bookTitle));
  		$asciiWriterName = $this->utf2ascii(strToLower($writerName));
  		$writer = new writer;
  		$sql = "
   		SELECT
    			$this->table.id,
    			$this->table.title,
    			$writer->table.id AS writerID,
    			$writer->table.name AS writerName
   		FROM $this->table   
   		LEFT JOIN $writer->table ON $this->table.writer = $writer->table.id
   		WHERE
    			$writer->table.asciiName = '$asciiWriterName'
   		AND
    			$this->table.asciiTitle = '$asciiBookTitle'
   		GROUP BY $this->table.id
  		";
  		$res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  		$book = mysql_fetch_object($res);
  		$writerID = $writer->create($writerName);
  		if ((!$book->id) or ($writerID != $book->writerID)) {
   		$sql = "INSERT INTO $this->table VALUES(
    			0,
    			'$bookTitle',
    			'$asciiBookTitle',
    			$writerID,
    			now()
   		)";
   		$res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
   		$book = $this->getInfo(mysql_insert_id());	
  		}
  		return $book;
 	}

	/**
	* Get title of all books.
	* @return book_mysql_query
	*/
	public function getAll() {
		$sql = "
			SELECT
				DISTINCT title
			FROM $this->table
		";
		$res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
		return $res;
	}

	/**
	* Get info by ID.
	* @link book::getInfoByItem()
	* @param int ID of book.
	* @return book_record
	*/
 	public function getInfo($id) {
  		return $this->getInfoByItem("id",$id);
 	}

	/**
	* Get info by item.
	* @param string Name of item.
	* @param string Value of item.
	* @retun book_record
	*/
 	public function getInfoByItem($item,$value) {
  		$writer = new writer;
  		$comment = new comment;
  		$opinion = new opinion;
  		$sql = "
   		SELECT
    			$this->table.id,
    			$this->table.title,
    			$this->table.asciiTitle,
    			$this->table.date,
    			$writer->table.id AS writerID,
    			$writer->table.name AS writerName,
    			COUNT($comment->table.id) AS commentCount,
    			ROUND(AVG($opinion->table.rating)) as rating,
    			COUNT($opinion->table.id) AS readed
   		FROM $this->table
   		LEFT JOIN $writer->table ON $this->table.writer = $writer->table.id
   		LEFT JOIN $comment->table ON $this->table.id = $comment->table.book
   		LEFT JOIN $opinion->table ON $this->table.id = $opinion->table.book
   		WHERE $this->table.$item = '$value'
   		GROUP BY $this->table.id
  		";
  		unset($writer);
  		$res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  		$record = mysql_fetch_object($res);
  		return $record; 
 	}

	/**
	* Create table in database.
	* @return void
	*/
 	public function install() {
  		$writer = new writer;
  		$sql = "CREATE TABLE $this->table (
   		id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
   		title VARCHAR(250),
   		asciiTitle VARCHAR(250) FULLTEXT,
   		writer INT(25),
   		date DATETIME default '0000-00-00 00:00:00',
   		FOREIGN KEY (writer) REFERENCES $writer->table (id)
  		)";
  		unset($writer);
  		mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql); 
 	}
	
	/**
	* Get books ordered by similarity.
	* @param int ID of book.
	* @param string Name of item what the returned items are ordered by.
	* @param int Number of page.
	* @return book_mysql_query
	*/
	public function getSimilar($bookID,$order,$page) {
  		$limitNumber = 20;
  		$limit = $page*$limitNumber;
  		switch ($order) {
   		default: $order = "ORDER BY similirity DESC";
   			break;
   		case "title": $order = "ORDER BY title";
   			break;
   		case "writerName": $order = "ORDER BY writerName";
   			break;
   		case "rating": $order = "ORDER BY rating DESC";
   			break;
   		case "readed": $order = "ORDER BY readed DESC";
   			break;
  		}
  		$tagReference = new tagReference;
  		$writer = new writer;
  		$opinion = new opinion;
  		$comment = new comment;  
  		$sql = "
		   SELECT
    			$this->table.id,
    			$this->table.title,
    			$this->table.asciiTitle,
    			$this->table.date,
    			$writer->table.id AS writerID,
    			$writer->table.name AS writerName,
    			ROUND(AVG($opinion->table.rating)) as rating,
    			COUNT($opinion->table.id) AS readed,
    			ROUND(
          		(
           			2*(SELECT COUNT(id) FROM $tagReference->table WHERE tag IN (SELECT tag FROM $tagReference->table WHERE book = $bookID) AND book = $this->table.id)
           			/
           			(SELECT COUNT(tag) FROM $tagReference->table WHERE book = $bookID OR book = $this->table.id)
          		)*100
         	) AS similirity,
    			(SELECT COUNT(id) FROM $this->table) AS pageHelp,
    			$limitNumber AS pageLimit
   		FROM reader_book
   		LEFT JOIN $tagReference->table ON $this->table.id = $tagReference->table.book
   		LEFT JOIN $writer->table ON $this->table.writer = $writer->table.id
   		INNER JOIN $opinion->table ON $this->table.id = $opinion->table.book
   		WHERE
    			$this->table.id != $bookID
   		GROUP BY $this->table.id
   		$order
   		LIMIT $limit, $limitNumber
  		";
  		unset($writer);
  		unset($comment);
  		unset($opinion);
  		unset($tagReference);  $res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  		return $res;  
 	} 
 
 	/**
 	* Return the newest books.
 	* @param int ID of user, not impearative.
 	* @param int Number of returned items.
 	* @return book_mysql_query
 	*/
 	public function last($user = false, $limit = 5) {
  		$writer = new writer;
  		$opinion = new opinion;
  		$comment = new comment;
  		if ($user) {
   		$condition = "WHERE $opinion->table.user = $user";
   		$order = "ORDER BY $opinion->table.date DESC";
   		$add = ",$opinion->table.id AS opinion";
  		}
  		else {
   		$order = "ORDER BY $this->table.date DESC";
   		$add = "";  
  		} 
  		$sql = "
   		SELECT
    			$this->table.id,
    			$this->table.title,
    			$this->table.asciiTitle,
    			$this->table.date,
    			$writer->table.id AS writerID,
    			$writer->table.name AS writerName,
    			ROUND(AVG($opinion->table.rating)) as rating,
    			COUNT($opinion->table.id) AS readed
    			$add
   		FROM $this->table
   		INNER JOIN $writer->table ON $this->table.writer = $writer->table.id
   		INNER JOIN $opinion->table ON $this->table.id = $opinion->table.book
   		$condition   		GROUP BY $this->table.id
   		$order
   		LIMIT 0,$limit
  		";
  		unset($writer);
  		unset($comment);
  		unset($opinion);
  		$res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  		return $res;
 	}
 
 	/**
 	* Return the best rated books.
 	* @param int ID of user, not impearative.
 	* @param int Number of returned items, not imperative.
 	* @return book_mysql_query
 	*/ 
 	public function top($user = false, $limit = 5) {
  		$writer = new writer;
  		$opinion = new opinion;
  		$comment = new comment;
  		if ($user) {
   		$condition = "WHERE $opinion->table.user = $user";
   		$add = ",$opinion->table.id AS opinion";
  		}
  		else {
   		$condition = "";
   		$add = "";
  		} 
  		$sql = "
   		SELECT
    			$this->table.id,
    			$this->table.title,
    			$this->table.asciiTitle,
    			$this->table.date,
    			$writer->table.id AS writerID,
    			$writer->table.name AS writerName,
    			ROUND(AVG($opinion->table.rating)) as rating,
    			COUNT($opinion->table.id) AS readed
    			$add
   		FROM $this->table
   		INNER JOIN $writer->table ON $this->table.writer = $writer->table.id
   		INNER JOIN $opinion->table ON $this->table.id = $opinion->table.book
   		$condition
   		GROUP BY $this->table.id
   		ORDER BY rating DESC, readed DESC, title
   		LIMIT 0,$limit
  		";
  		$res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  		return $res;
 	}
 
}
?>
