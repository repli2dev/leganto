<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/
class template extends reader {
	/**
	* @var string Direction, where files with HTML are.
	* @return void
	*/	
	public $dir "./template/";

	/**
	* @var string Direction, where file with CSS is.
	* @return void
	*/
 	public $dirCSS "style/";
 
	/**
	* User inhereted construct
	* @return void
	*/ 	
	public function __construct() {
  		parent::__construct(); 
 	}

	/**
	* Show addon by book.
	* @var int Book ID.
	* @return void
	*/
 	public function addonByBook($bookID) {
  		$addon = new addon;
  		$res = $addon->getByBook($bookID);
  		unset($addon);
  		$texy = new texy;
  		$texy->utf = TRUE;
  		$lng = new language;
  		$user = new user;
  		$levelCom = $user->levelCommon;
  		unset($user);
  		if ((mysql_num_rows($res) == 0) and ($this->owner->level > $levelCom)) {
  			echo "<h3><a href=\"moderator.php?action=addonForm&amp;book=$bookID\" title=\"$lng->addAddon\">$lng->addAddon</a></h3>";
  		}
  		while($addon = mysql_fetch_object($res)) {
   		$addon->content = $texy->process($addon->content);
   		require($this->dir."addon.php");
  		}
  		unset($lng);
 	}

	/**
	* Create form to send e-mail. Only for admin.
	* @var string Action of the form.
	* @return void
	*/
 	public function adminFormEmailSend($action = "admin.php?action=sendEmail") {
  		$lng = new language;
  		$user = new user;
  		$user = $user->getInfo($_GET[user]);
  		require($this->dir."adminFormEmailSend.php");
  		unset($lng);   
 	}

	/**
	* Create result for searching of users.
	* @var string Order of users.
	* @var int Page.
	* @var string Searched user's name.
	* @return void
	*/
 	public function adminUserSearch($order,$page,$userName) {
  		$admin = new admin;
  		$res = $admin->userSearch($order,$page,$userName);
  		unset($moderator);
  		echo "<table>";
  		$this->adminUserSearchHead();
  		while ($user = mysql_fetch_object($res)) {
   		$pageMax = ($user->pageHelp-$user->pageHelp%$user->pageLimit)/$user->pageLimit; 
   		$user->login = $this->dateFormatShort($user->login);
   		$this->adminUserSearchItem($user);  
  		}
  		echo "</table>";
  		$this->page("admin.php?action=userSearch&amp;name=$userName",$page,$pageMax);
 	} 

	/**
	* Header of table for user searching.
	* @return void
	*/
	public function adminUserSearchHead() {
  		$lng = new language;
  		require($this->dir."adminUserSearchHead.php");
  		unset($lng);
 	}
 	
 	/**
 	* Item for user searching.
 	* @var user_record
 	* @return void
 	*/
	public function adminUserSearchItem($user) {
  		$lng = new language;
  		require($this->dir."adminUserSearchItem.php");
  		unset($lng);
 	}

	/**
	* Show advert by book.
	* @var int Book ID.
	* @return void.
	*/
	public function advertisement($bookID) {
  		$advert = new advertisement;
  		$res = $advert->getByBook($bookID);
  		unset($advert);
	  	while($advert = mysql_fetch_object($res)) {
   		$this->box($advert->content,"advertisement");		
  		}
 	}

	/**
	* Show books by favourite users.
	* @var int Use ID.
	* @var int Number of showed books.
	*/
	public function bookByFavourite($usID,$limit = 10) {
  		$book = new book;
  		$res = $book->byFavourite($usID,$limit);
  		unset($book);  
  		if (mysql_num_rows($res)) {   
   		echo "<div class=\"bookByFavourite\">";
   		$this->html($lng->bookByFavourite,"h3");
    		while ($book = mysql_fetch_object($res)) {
    			$this->bookByFavouriteItem($book);
   		}
   		echo "</div>";
  		}
 	}

	/**
	* @var book_record
	* @return void
	*/
	public function bookByFavouriteItem($book) {
  		require($this->dir."bookByFavouriteItem.php"); 
	}

	/**
	* Show table of book by user.
	* @var int User ID.
	* @var string Order of the books.
	* @var int Page.
	* @return void
	*/
	public function bookByUser($usID,$order,$page) {
  		$book = new book;
  		$res = $book->byUser($usID,$order,$page);
  		unset($book);
  		$this->bookByUserHead();
  		while($book = mysql_fetch_object($res)) {
   		$this->bookByUserItem($book);
   		$pageMax = ($book->pageHelp-$book->pageHelp%$book->pageLimit)/$book->pageLimit;
  		}
  		echo "</table>";
  		$this->page("user.php?user=$usID&amp;order=".$_GET[order]."&amp;action=bookAll",$_GET["page"],$pageMax);
 	}


	/**
	* Create box.
	* @var string Content of box.
	* @var string Class of box.
	* @return void
	*/
	public function box($content,$class="box") {
		$texy = new texy;
		$texy->utf = TRUE;
		$content = $texy->process($content);
		unset($texy);
		require($this->dir."link.php");
	}

	/**
	* Create form to add addon.
	* @var int Book ID.
	* @var int Addon ID.
	* @return void
	*/
 	public function formAddon($bookID,$addonID = NULL) {
  		if ($addonID) {
   		$addon = new addon;
   		$addon = $addon->getByID($addonID);
   		$action = "moderator.php?action=addonChange&amp;addon=$addonID"; 
  		}  
  		else {
   		$action = "moderator.php?action=addonCreate";
  		}
  		$lng = new language;
  		require($this->dir."formAddon.php");
  		unset($lng);  
 	}

	/**
	* Create form to add book to system.
	* @var int Book ID.
	* @return void
	*/
 	public function formBookAdd($bookID) {
  		$action = "opinionAction.php?action=add&amp;user=".$this->owner->id;
  		$lng = new language;
  		$book = new book;
  		$book = $book->getInfo($bookID);
  		$bookTitle = $book->title;
  		$writer = new writer;
  		$writerNameFirst = $writer->getNameFirst($book->writerName);
  		$writerNameSecond = $writer->getNameSecond($book->writerName);
  		unset($book);
  		require($this->dir."formBookAdd.php");
  		unset($lng);
 	}

	/**
	* Create form to add comment.
	* @var int Book ID.
	* @return void
	*/
 	public function formCommentAdd($bookID) {
  		$action = "book.php?action=addComment&amp;book=$bookID";
  		$lng = new language;
  		if ($this->owner->level) { require($this->dir."addCommentFormIn.php"); } 
 	}

	/**
	* Create form to add Discuss item.
	* @var string Action of form.
	* @return void
	*/
	public function formDiscussAdd($action = "discussion.php?action=addDiscuss") {
  		if ($this->owner->id) {  
   		$lng = new language;
  			require($this->dir."formDiscussAdd.php");
   		unset($lng);
  		}
 	}

	/**
	* Create form to add tag.
	* @var int Book ID.
	* @return void
	*/
	public function formTagAdd($bookID) {
  		$action = "book.php?action=addTag&amp;book=$bookID";
  		$lng = new language;
  		require($this->dir."formTagAdd.php");
  		unset($lng);  
 	}

	/**
	* Create tag.
	* @var string Content of tag.
	* @var string Name of tag.
	* @var string Class of tag.
	* @return void.
	*/
	public function html($item,$tag,$class=NULL) {
		echo "<$tag>$item</$tag>";
	}

	/**
	* Create link.
	* @var string URL of link.
	* @var string Caption of link.
	* @var string Class of link.
	* @return void
	*/
	public function link($url,$caption,$class=NULL) {
		require($this->dir."link.php");
	}

	/**
	* Create link to add book to reader's book.
	* @var int Book ID.
	* @return void
	*/
 	public function linkBookAdd($bookID) {
  		if ($this->owner->id) {
   		$opinion = new opinion;
   		if (!$opinion->isMine($bookID)) {  
    			$lng = new language;
    			$this->link("opinionAction.php?action=addForm&amp;book=$bookID",$lng->addBook);
    			unset($lng);
   		}
  		}
 	}

	/**
	* Create link in list.
	* @var string URL of link.
	* @var string Caption of link.
	* @return void
	*/
	public function linkInList($url,$caption,$class=NULL) {
		require($this->dir."linkInList.php");
	}

	/**
	* Create box with the most used tags.
	* @var int Number of tags.
	* @return void
	*/
	public function tagListTop($limit = 60) {
  		$tag = new tag;
  		$res = $tag->getListTop($limit);
  		unset($tag);
  		$search = new search;
  		$lng = new language;
  		echo "<div id=\"tagList\"><h3>$lng->tags</h3>";
  		unset($lng);
  		while ($tag = mysql_fetch_object($res)) {
	   	$urlTag = $search->url($tag->name);   
	   	require($this->dir."tagListItem.php");
  		}
  		echo "</div>";  
 	} 

?>
