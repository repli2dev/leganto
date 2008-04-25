<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/

/**
* @package reader
* This class is used for admin work.
*/
class admin extends reader {
	
	/**
	* Constructor.
	* @return void
	*/	
	public function __construct() {
  		parent::__construct();
  		$user = new user;
  		$lng = new language;
  		if ($this->owner->level < $user->levelAdmin) die($lng->accessDenied);
 	}
 
 	/**
 	* Make the user banned.
 	* @param int ID of user.
 	*/
 	public function userBan($userID) {
  		$user = new user;
  		$user->ban($userID);
  		unset($user);	 
 	} 

	/**
	* @param string Name of item what the returned items are ordered by.
	* @param int Number of page.
	* @param string Searched item.
	* @return user_mysql_query 
	*/
 	public function userSearch($order,$page,$name = "") {
  		$user = new user;
  		$return = $user->listAll($order,$page,$name);
  		return $return; 
 	}
}