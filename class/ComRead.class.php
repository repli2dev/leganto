<?php
require_once("./eskymofw/Autoload.class.php");

Autoload::add("");
Autoload::add("eskymofw");
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/

/**
* Tato trida je urcena pro praci s tabulkou, kde jsou ulozeny informace o prectenych komentarich.
* @package reader
*/
class Comment extends MySQLTable {
	
	/**
	* @var string Nazev tabulky
	*/
	const TABLE_NAME = "comRead";

	/**
	* @var array_string Nazvy poli tabulku v databazi, ktera se vzdy musi vyplnit.
	*/
	private static $importantColumns = array("user","comment");

	/**
	* Nastavi komentare k dane knize jako prectene (z pohledu prihlaseneho uzivatele)
	* @param int ID knihy.
	* @return void.
	*/ 
 	public function actual($bookID) {
 		$user = Page::session("login");
  		if ($user->id) { 
	   		$sql = "
	    			SELECT ".ComFavourite::getTableName().".id FROM ".Book::getTableName()."
	    			LEFT JOIN ".Opinion::getTableName()." ON ".Book::getTableName().".id = ".Opinion::getTableName().".book
	    			LEFT JOIN ".ComFavourite::getTableName()." ON ".Book::getTableName().".id = ".ComFavourite::getTableName().".book
	    			WHERE
	     				(".Opinion::getTableName().".user = $user->id AND ".Opinion::getTableName().".book = $bookID)
	     			OR
	     				(".ComFavourite::getTableName().".user AND ".ComFavourite::getTableName().".book = $bookID)
	    			GROUP BY $comFavourite->table.id
	   		";
   			$res = MySQL::query($sql,__FILE__,__LINE__);
   		if (mysql_num_rows($res) > 0) {
    			$sql = "
	  				SELECT ".Comment::getTableName().".id
	  				FROM ".Comment::getTableName()."
	  				WHERE ".Comment::getTableName().".id NOT IN ((SELECT ".self::getTableName().".comment FROM ".self::getTableName()." WHERE ".self::getTableName().".user = $user->id))
	  				AND ".Comment::getTableName().".book = $bookID    
    			";	  
    			$res = MySQL::query($sql,__FILE__,__LINE__);
    			while ($comment = mysql_fetch_object($res)) {
					parent::create(array("user" => $user->id,"comment" => $comment->id));
	 			} 
   		}
  		}
 	} 

 	/**
	* Vrati komentare, ktere dany uzivatel neprecetl.
	* @param int ID uzivatele.
	* @return mysql_result
	*/
 	public function getNotRead($usID) {
  		$sql = "
	   		SELECT
	    			COUNT(DISTINCT ".Comment::getTableName().".id) AS comNumber,
	    			".Book::getTableName().".id AS bookID,
	    			".Book::getTableName().".title AS bookTitle 
	   		FROM ".Comment::getTableName()."
			LEFT JOIN ".self::getTableName()." ON ".Comment::getTableName().".id = ".self::getTableName().".comment
	   		LEFT JOIN ".Book::getTableName()." ON ".Comment::getTableName().".book = ".Book::getTableName().".id
	   		LEFT JOIN".Opinion::getTableName()." ON ".Comment::getTableName().".book = ".Opinion::getTableName().".book
	   		LEFT JOIN ".ComFavourite::getTableName()." ON ".Book::getTableName().".id = ".ComFavourite::getTableName().".book		
	   		WHERE
	   			".Comment::getTableName().".id NOT IN ((SELECT ".self::getTableName().".comment FROM ".self::getTableName()." WHERE ".self::getTableName().".user = $usID))
	   		AND
	   			(".Opinion::getTableName().".user = $usID OR ".ComFavourite::getTableName().".user = $usID)   
	   		GROUP BY bookID
		";
		return MySQL::query($sql,__FILE__,__LINE__);
 	} 
 	
	/**
	* Create table in database.
	* @return void.
	*/
 	public function install() {
  		$comment = new comment;
  		$sql = "CREATE TABLE $this->table (
   		id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
   		user INT(25) NOT NULL,
   		comment INT(25) NOT NULL,
   		FOREIGN KEY (user) REFERENCES $user->table (id),
   		FOREIGN KEY (comment) REFERENCES $comment->table (id)
  		)";
  		unset($comment);
  		mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
 	}	
	
}
?>