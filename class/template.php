<?php
class template extends reader {
 public $dir;
 public $dirCSS;
 
 public function __construct() {
  parent::__construct();
  $this->dir = "./template/";
  $this->dirCSS = "style/";
 }

 public function addAdvertForm($action = "admin.php?action=newAdvert") {
  $lng = new language;
  require($this->dir."addAdvertForm.php");
  unset($lng); 
 }

 public function addBookForm($bookID) {
  $action = "opinionAction.php?action=add&amp;user=".$this->owner->id;
  $lng = new language;
  $book = new book;
  $book = $book->getInfo($bookID);
  $bookTitle = $book->title;
  $writer = new writer;
  $writerNameFirst = $writer->getNameFirst($book->writerName);
  $writerNameSecond = $writer->getNameSecond($book->writerName);
  unset($book);
  require($this->dir."addBookForm.php");
  unset($lng);
 }

 public function addBookLink($bookID) {
  if ($this->owner->id) {
   $opinion = new opinion;
   if (!$opinion->isMine($bookID)) {  
    $lng = new language;
    require($this->dir."addBookLink.php");
    unset($lng);
   }
  }
 }

 public function addCommentForm() {
  $action = "book.php?action=addComment&amp;book=".$_GET["book"];
  $lng = new language;
  if ($this->owner->level) { require($this->dir."addCommentFormIn.php"); } 
 }
 
 public function addDiscussForm($action = "discussion.php?action=addDiscuss") {
  if ($this->owner->id) {  
   $lng = new language;
  	require($this->dir."addDiscussForm.php");
   unset($lng);
  }
 }

 public function addonForm($bookID,$addonID = NULL,$bookID) {
  if ($addonID) {
   $addon = new addon;
   $addon = $addon->getByID($addonID);
   $action = "moderator.php?action=addonChange&amp;addon=$addonID"; 
  }  
  else {
   $action = "moderator.php?action=addonCreate";
  }
  $lng = new language;
  require($this->dir."addonForm.php");
  unset($lng);  
 }
 
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

 public function addTagForm($bookID) {
  $action = "book.php?action=addTag&amp;book=$bookID";
  $lng = new language;
  require($this->dir."addTagForm.php");
  unset($lng);  
 }

 public function adminSendEmailForm($action = "admin.php?action=sendEmail") {
  $lng = new language;
  $user = new user;
  $user = $user->getInfo($_GET[user]);
  require($this->dir."adminSendEmailForm.php");
  unset($lng);   
 } 
 
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
 
 public function adminUserSearchHead() {
  $lng = new language;
  require($this->dir."adminUserSearchHead.php");
  unset($lng);
 } 
 
 public function adminUserSearchItem($user) {
  $lng = new language;
  require($this->dir."adminUserSearchItem.php");
  unset($lng);
 } 
 
 public function adminUserSearchForm($action = "admin.php") {
  $lng = new language;
  require($this->dir."userSearchForm.php");
  unset($lng);
 }

 public function advertisement($bookID) {
  $advert = new advertisement;
  $res = $advert->getByBook($bookID);
  unset($advert);
  $texy = new texy;
  $texy->utf = TRUE;
  while($advert = mysql_fetch_object($res)) {
   $advert->content = $texy->process($advert->content);
   require($this->dir."advertisement.php");
  }
 } 
 
 public function bookByFavourite($usID,$limit = 10) {
  $book = new book;
  $res = $book->byFavourite($usID,$limit);
  unset($book);  
  if (mysql_num_rows($res)) {   
   $this->bookByFavouriteHead($usID);
    while ($book = mysql_fetch_object($res)) {
    $this->bookByFavouriteItem($book);
   }
   echo "</div>";
  }
 } 

 public function bookByFavouriteHead($usID) {
  $user = new user;
  $user = $user->getInfo($usID);
  $lng = new language;
  require($this->dir."bookByFavouriteHead.php");
  unset($lng);
 }

 public function bookByFavouriteItem($book) {
  require($this->dir."bookByFavouriteItem.php"); 
 }
 
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

 public function bookByUserHead() {
  $lng = new language;
  $url = "user.php?user=".$_GET["user"]."&amp;action=bookAll&amp;order=";
  require($this->dir."bookByUserHead.php");
  unset($lng);    
 }

 public function bookByUserItem($book) {
  $search = new search;
  $urlWriter = $search->url($book->writerName);
  unset($search);
  require($this->dir."bookByUserItem.php");
 } 

 public function bookInfo($bookID) {
  $lng = new language;
  $bookClass = new book;
  $book = $bookClass->getInfo($bookID);
  unset($bookClass);
  $search = new search;
  $urlWriter = $search->url($book->writerName);
  unset($search);
  require($this->dir."bookInfo.php");
  unset($lng);
  $this->addonByBook($bookID);
  $this->tagListByBook($bookID);
  if ($this->owner->id) $this->addTagForm($bookID);
  $this->advertisement($bookID);
  $this->makeCommentFavouriteLink($bookID);	
  $this->commentRead($bookID);
  $this->addCommentForm();
  echo "</div>";
 }

 public function comment($bookID,$comment,$gravatar,$number) {
  $lng = new language;
  $user = new user;
  require($this->dir."comment.php");
  unset($lng);
  unset($user); }

 public function commentLast($limit = 5) {
  $comment = new comment;
  $res = $comment->lastCommentedBooks($limit);
  $lng = new language;
  echo "<div><h3>$lng->commentLast</h3><ul>";
  unset($lng);
  while($comment = mysql_fetch_object($res)) {
	$this->commentLastItem($comment);
  }
  echo "</ul></div>";
 }

 public function commentLastItem($comment) {
  $lng = new language;
  require($this->dir."commentLastItem.php");
  unset($lng);
 }

 public function commentRead($bookID) {
  $comment = new comment;
  $res = $comment->read($bookID);
  if (mysql_num_rows($res) > 0) {    
   $user = new user;
   $number = 0;
   $texy = new texy;
   $texy->allowedTags = array("p" => TRUE,"br" => TRUE);
   $texy->utf = TRUE;
   $lng = new language;
   echo "<h3>$lng->comments:</h3><div class=\"commenRead\">";
   while ($record = mysql_fetch_object($res)) {
    $record->text = $texy->process($record->text);
    $number++;
    $gravatar = md5($record->email);
    $gravatar = "http://www.gravatar.com/avatar.php?gravatar_id=$gravatar&amp;size=40&amp;default=http%3A%2F%2Fjargan.ic.cz%2Fimage%2Fgravatar.png";
    $record->date = $this->dateFormat($record->date);
	 $this->comment($bookID,$record,$gravatar,$number);
   }
   echo "</div>";
   unset($user);
   $comRead = new comRead;
   $comRead->actual($bookID);
  }
  unset($comment);
 }
 
 public function comNotRead($usID) {
  if ($usID == $this->owner->id) {
   $comRead = new comRead;
   $res = $comRead->getNotRead($this->owner->id);
   if (mysql_num_rows($res) > 0) {   
    $lng = new language;
    echo "<div><h3>$lng->comNotReaded</h3><ul>";
    unset($lng);
    while($comment = mysql_fetch_object($res)) {
     $this->comNotReadItem($comment);   
    }
    echo "</ul></div>";
   }
  }
 } 
 
 public function comNotReadItem($comment) {
  $lng = new language;
  require($this->dir."comNotReadItem.php");
  unset($lng);
 } 
 
 public function discussionItem($dis) {
  $lng = new language;
  $user = new user;
  require($this->dir."discussionItem.php");
  unset($lng);
  unset($user);  
 } 
 
 public function discussionRead($page) {
  $dis = new discussion;
  $res = $dis->read($page);
  unset($dis);
  $lng = new language;
  echo "<h1>$lng->discussion</h1>";
  unset($lng);
  $this->addDiscussForm();
  $texy = new texy;
  $texy->allowedTags = array("p" => TRUE,"br" => TRUE);
  $texy->utf = TRUE;
  while($dis = mysql_fetch_object($res)) {
   $dis->text = $texy->process($dis->text);
   $gravatar = md5($record->email);
   $gravatar = "http://www.gravatar.com/avatar.php?gravatar_id=$gravatar&amp;size=40&amp;default=http%3A%2F%2Fjargan.ic.cz%2Fimage%2Fgravatar.png";
   $dis->date = $this->dateFormat($dis->date);
   $this->discussionItem($dis);
  }
  $this->page("discussion.php?help=help",$page,$pageMax);
 }

 public function footer() {
  $lng = new language;
  require($this->dir."footer.php");
  unset($lng);
 }

 public function header($title = "") {
  $lng = new language;
  require($this->dir."header.php");
  unset($lng);
 }
 
 public function introduction() {
  require($this->dir."introduction.php");
 } 
 
 public function login() {
  if ($this->owner->id) {
   $lng = new language;
   require($this->dir."loginInfo.php");
   unset($lng);   
  }
  else $this->loginForm();  
 } 
 
 public function loginForm($action = "user.php?action=in") {
  $lng = new language;
  require($this->dir."loginForm.php");
  unset($lng);   
 } 

 public function makeCommentFavouriteLink($bookID) {
  if ($this->owner->id) {   
   $lng = new language;
   $comFavourite = new comFavourite;
   $isFavourite = $comFavourite->isFavourite($bookID);
   unset($comFavourite);
   require($this->dir."makeCommentFavouriteLink.php");
   unset($lng);  
  }
 }

 public function menu() {
  $lng = new language;
  require($this->dir."menu.php");
  unset($lng);    
 }
 
 public function message($message) {
  require($this->dir."message.php");
 }

 public function messageForm($userName = NULL,$action = "message.php?action=send&amp;suggest=yes") {
  $lng = new language;
  require($this->dir."messageForm.php");
  unset($lng);
 }
 
 public function messageRead() {
  echo "<div id=\"messageRead\">";
  $this->messageReadRecieved();
  $this->messageReadSent();
  echo "</div>";
 } 
 
 public function messageReadItem($message) {
  $lng = new language;
  require($this->dir."messageReadItem.php");
  unset($lng);
 } 
 
 public function messageReadRecieved() {
  $lng = new language;
  $mes = new message;
  $res = $mes->readRecieved();
  unset($mes);
  echo "<div id=\"messageReadRecieved\"><h2>$lng->recieved</h2>";
  $texy = new texy;
  $texy->allowedTags = array("p" => TRUE,"br" => TRUE);
  $texy->utf = TRUE;  
  while ($mes = mysql_fetch_object($res)) {
  	$mes->content = $texy->process($mes->content);
  	$mes->date = $this->dateFormat($mes->date);
  	$this->messageReadItem($mes);
  }
  echo "</div>";
  unset($lng);
 } 
 
 public function messageReadSent() {
  $lng = new language;
  $mes = new message;
  $res = $mes->readSent();
  unset($mes);
  echo "<div id=\"messageReadSent\"><h2>$lng->sent</h2>";
  $texy = new texy;
  $texy->allowedTags = array("p" => TRUE,"br" => TRUE);
  $texy->utf = TRUE;  
  while ($mes = mysql_fetch_object($res)) {
  	$mes->content = $texy->process($mes->content);
  	$mes->date = $this->dateFormat($mes->date);
  	$this->messageReadItem($mes);
  }
  echo "</div>";
  unset($lng);
 } 
 
 public function messageSendLink($name = NULL) {
  $url = "message.php?action=sendForm&amp;userName=$name&amp;suggest=yes";
  $lng = new language;
  require($this->dir."messageSendLink.php");
  unset($lng);
 } 
 
 public function middle() {
  require($this->dir."middle.php");
  $this->login(); 
 } 
 
 public function moderatorBookConnectForm($action = "moderator.php?action=bookConnect") {
  $lng = new language;
  require($this->dir."moderatorBookConnectForm.php");
  unset($lng); 
 } 
 
 public function moderatorBookEditForm($action = "moderator.php?action=bookEdit") {
  $book = new book;
  $book = $book->getInfo($_GET[book]);
  $lng = new language;
  require($this->dir."moderatorBookEditForm.php");
  unset($lng);    
 } 
 
 public function moderatorBookSearch($title,$order,$page) {
  $search = new search;
  $res = $search->search("bookTitle",$title,$order,$page);
  $this->moderatorBookSearchHead();
  while ($book = mysql_fetch_object($res)) {
   $this->moderatorBookSearchItem($book);
   $pageMax = ($book->pageHelp-$book->pageHelp%$book->pageLimit)/$book->pageLimit;
  }
  echo "</table>"; 
  $this->page("moderator.php?searchValue=".$_GET[searchValue]."&amp;order=".$_GET[order]."&amp;action=".$_GET[action],$_GET["page"],$pageMax);
 } 
 
 public function moderatorBookSearchHead() {
  $url = "moderator.php?searchValue=$_GET[searchValue]&amp;action=bookSearch&amp;order=";
  $lng = new language;
  require($this->dir."moderatorBookSearchHead.php");
  unset($lng);      
 }
 
 public function moderatorBookSearchItem($book,$action = "moderator.php?action=bookEdit") {
  $lng = new language;
  require($this->dir."moderatorBookSearchItem.php");
  unset($lng);       
 } 

 public function moderatorMenu() {
  $lng = new language;
  require($this->dir."moderatorMenu.php");
  unset($lng);
 }
 

 public function moderatorSearchForm($action = "moderator.php") {
  $lng = new language;
  require($this->dir."moderatorSearchForm.php");
  unset($lng);
 }

 public function moderatorTagConnectForm($action = "moderator.php?action=tagConnect") {
  $lng = new language;
  require($this->dir."moderatorTagConnectForm.php");
  unset($lng);
 } 
 
 public function moderatorTagSearch($tagValue,$order,$page) {
  $tag = new tag;
  $res = $tag->search($tagValue,$order,$page);
  unset($tag);
  $this->moderatorTagSearchHead();
  while ($tag = mysql_fetch_object($res)) {
   $this->moderatorTagSearchItem($tag);
   $pageMax = ($tag->pageHelp-$tag->pageHelp%$tag->pageLimit)/$tag->pageLimit;
  }
  echo "</table>"; 
  $this->page("moderator.php?searchValue=".$_GET[searchValue]."&amp;order=".$_GET[order]."&amp;action=".$_GET[action],$_GET["page"],$pageMax);
 }
 
 public function moderatorTagSearchHead() {
  $url = "moderator.php?searchValue=$_GET[searchValue]&amp;action=$_GET[action]&amp;order=";
  $lng = new language;
  require($this->dir."moderatorTagSearchHead.php");
  unset($lng);
 }
 
 public function moderatorTagSearchItem($tag, $action = "moderator.php?action=tagEdit") {
  $lng = new language;
  require($this->dir."moderatorTagSearchItem.php");
  unset($lng);
 }  
 
 public function moderatorWriterSearch($writerName,$order,$page) {
  $writer = new writer;
  $res = $writer->search($writerName,$order,$page);
  unset($writer);
  $this->moderatorWriterSearchHead();
  while($writer = mysql_fetch_object($res)) {
  	$this->moderatorWriterSearchItem($writer);
  	$pageMax = ($writer->pageHelp-$writer->pageHelp%$writer->pageLimit)/$writer->pageLimit;
  }
  echo "</table>"; 
  $this->page("moderator.php?searchValue=".$_GET[searchValue]."&amp;order=".$_GET[order]."&amp;action=".$_GET[action],$_GET["page"],$pageMax);
 }

 public function moderatorWriterSearchHead() {
  $url = "moderator.php?searchValue=$_GET[searchValue]&amp;action=$_GET[action]&amp;order=";
  $lng = new language;
  require($this->dir."moderatorWriterSearchHead.php");
  unset($lng);
 }

 public function moderatorWriterSearchItem($writer, $action = "moderator.php?action=writerEdit") {
  $lng = new language;
  require($this->dir."moderatorWriterSearchItem.php");
  unset($lng);
 }  
 
 public function opinionList($book) {
  $opinion = new opinion;
  $lng = new language;	
  $res = $opinion->getListByBook($book);
  unset($opinion);
  echo "<div id=\"opinionList\">";
  echo "<h3>$lng->bookWasReading</h3>";  
  while ($op = mysql_fetch_object($res)) {
   require($this->dir."opinionListItem.php");
  }
  echo "</div>";
 } 
 
 public function newBook($limit = 10) {
  $lng = new language;
  $book = new book;
  $resBook = $book->last($user = false,$limit);
  echo "<div id=\"newBook\"><h3>$lng->lastBook</h3><table rules=\"groups\">";
  unset($lng);
  while ($record = mysql_fetch_object($resBook)) {
   $this->newBookItem($record);
  }
  echo "</table></div>";
 }
 
 public function newBookByUser($user, $limit = 5) {
  $lng = new language;
  $book = new book;
  $resBook = $book->last($user,$limit);
  echo "<div id=\"newBook\"><h3>$lng->lastBook</h3><table rules=\"groups\">";
  unset($lng);
  while ($record = mysql_fetch_object($resBook)) {
   $this->newBookByUserItem($record);
  }
  echo "</table></div>";

 }

 public function newBookByUserItem($book) {
  $search = new search;
  $urlWriter = $search->url($book->writerName);
  unset($search);
  require($this->dir."newBookByUserItem.php");
 }

 public function newBookItem($book) {
  $search = new search;
  $urlWriter = $search->url($book->writerName);
  unset($search);
  require($this->dir."newBookItem.php");
 }
 
 public function opinion($opID) {
  $lng = new language;
  $texy = new texy;
  $texy->utf = TRUE;
  $opinion = new opinion;
  $op = $opinion->getInfoByID($_GET["opinion"]);
  unset($opinion); 
  $search = new search;
  $urlWriter = $search->url($op->writerName);
  unset($search);
  $op->content = $texy->process($op->content);
  $user = new user;
  $user = $user->getInfo($op->userID);
  unset($texy);
  require($this->dir."opinion.php");
  unset($lng); 
 }

 public function opinionChangeForm($opinionID,$action = false) {
  if (!$action) $action = "opinionAction.php?opinion=$opinionID&amp;action=change";
  $lng = new language;
  $opinion = new opinion;
  $op = $opinion->getInfoByID($opinionID);
  unset($opinion);
  require($this->dir."opinionChangeForm.php");
  unset($lng);
 }

 public function allUserBookLink($userID = false) {
  $lng = new language;
  if (!$userID) $userID = $_GET[user];
  require($this->dir."allUserBookLink.php");
  unset($lng);
 }

 public function page($url,$page,$pageMax = "-1") {
  $backward = $page - 1;
  $forward =  $page + 1;
  $lng = new language;
  require($this->dir."page.php");
  unset($lng);  
 }

 public function registrationForm($action = "user.php?action=create") {
  $lng = new language;
  require($this->dir."registrationForm.php");
  unset($lng);
 }
 
 public function search($type,$value,$order,$page) {
  $search = new search;
  $lng = new language;
  $res = $search->search($type,$value,$order,$page);
  $help = mysql_num_rows($res);
  unset($search);
  echo "<p>$lng->searchProduct \"<strong>$value</strong>\":</p>";
  if ($help) {
   $this->searchItemHead();
   while ($book = mysql_fetch_object($res)) {
    $this->searchItem($book);
    $pageMax = ($book->pageHelp-$book->pageHelp%$book->pageLimit)/$book->pageLimit;
   }
   echo "</table>"; 
   $this->page("search.php?searchWord=".$_GET["searchWord"]."&amp;column=".$_GET["column"]."&amp;order=".$_GET[order],$_GET["page"],$pageMax);
  }  
  else echo "<p>$lng->noSearchResult</p>";
 } 
 
 public function searchSimilirity($bookID,$order,$page) {
  $book = new book;
  $res = $book->getSimilar($bookID,$order,$page);
  $book = $book->getInfo($bookID);
  $this->searchSimilirityHead($book);
  unset($book);
  while($book = mysql_fetch_object($res)) {
   $this->searchSimilirityItem($book);  
   $pageMax = ($book->pageHelp-$book->pageHelp%$book->pageLimit)/$book->pageLimit;
  }
  echo "</table>";
  $this->page("book.php?book=".$_GET["book"]."&amp;action=similarity",$_GET["page"],$pageMax);
 } 
 
 public function searchSimilirityHead($book) {
  $lng = new language;
  $url = "book.php?book=$book->id&amp;action=similarity&amp;order=";
  require($this->dir."searchSimilirityHead.php");
  unset($lng);  
 } 
 
 public function searchSimilirityItem($book) {
  $lng = new language;
  $search = new search;
  $urlWriter = $search->url($book->writerName);
  unset($search);
  require($this->dir."searchSimilirityItem.php");
  unset($lng);
 } 
 
 public function searchSimilarityLink($bookID) {
  $lng = new language;
  require($this->dir."searchSimilarityLink.php");
  unset($lng);  
 } 
 
 public function searchItem($book) {
  $lng = new language;
  $search = new search;
  $urlWriter = $search->url($book->writerName);
  unset($search);
  require($this->dir."searchItem.php");
  unset($lng); 
 } 
 
 public function searchItemHead() {
  $lng = new language;
  $url = "search.php?searchWord=".$_GET["searchWord"]."&amp;column=".$_GET["column"]."&amp;order=";
  require($this->dir."searchItemHead.php");
  unset($lng);    
 }
  
 public function tagListByBook($bookID) {
  $tag = new tag;
  $res = $tag->getByBook($bookID);
  unset($tag);
  $search = new search;
  echo "<div id=\"tagList\">";
  while ($tag = mysql_fetch_object($res)) {
   $urlTag = $search->url($tag->name);   
   require($this->dir."tagListItem.php");
  }
  echo "</div>";
 }
 
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
 
 public function topBook($limit = 10) {
  $book = new book;
  $resBook = $book->top($user = false, $limit);
  $lng = new language;
  echo "<div id=\"topBook\"><h3>$lng->topBook</h3><table rules=\"groups\">";
  unset($lng);
  while ($record = mysql_fetch_object($resBook)) {
   $this->topBookItem($record);
  }
  echo "</table></div>";
 }
 
 public function topBookByUser($user, $limit = 5) {
  $book = new book;
  $resBook = $book->top($user, $limit);
  $lng = new language;
  echo "<div id=\"topBook\"><h3>$lng->topBook</h3><table rules=\"groups\">";
  unset($lng);
  while ($record = mysql_fetch_object($resBook)) {
   $this->topBookByUserItem($record);
  }
  echo "</table></div>";
 }

 public function topBookByUserItem($book) {
  $search = new search;
  $urlWriter = $search->url($book->writerName);
  unset($search);
  require($this->dir."topBookByUserItem.php");
 }
 
 public function topBookItem($book) {
  $search = new search;
  $urlWriter = $search->url($book->writerName);
  unset($search);
  require($this->dir."topBookItem.php");
 }
 
 public function userChangeIcoForm($action = "./ico/ico.php") {
  $lng = new language;
  require($this->dir."userChangeIcoForm.php");
  unset($lng);
 } 
 
 public function userChangeMeForm($action = "user.php?action=change") {
  $lng = new language;
  $user = new user;
  $user = $user->getInfo($this->owner->id);
  require($this->dir."userChangeMeForm.php");
  unset($lng);
  unset($user);
  $this->userChangeIcoForm();  
 }
 
 public function userFavourite($userID) {
  $recommend = new recommend;
  $res = $recommend->byUser($userID);
  unset($recommend);
  $lng = new language;
  if (mysql_num_rows($res)) {
   echo "<div id=\"userList\"><h3>$lng->userFavourite -<a href=\"rss.php?action=favouriteGroup&amp;user=$userID\" title=\"$lng->userFavourite - RSS\">rss</a></h3>";
   unset($lng);  
   while($user = mysql_fetch_object($res)) {
    $this->userFavouriteItem($user);
   }
   echo "</div>";
  }
 }
 
 public function userFavouriteItem($user) {
  $lng = new language; 
  require($this->dir."userFavouriteItem.php");
 } 
 
 public function userInfo($userID) {
  $lng = new language;
  $userClass = new user;
  $user = $userClass->getInfo($userID);
  $texy = new texy;
  $texy->utf = true;
  $user->description = $texy->process($user->description);
  unset($texy);
  $urlMakeFavourite = "user.php?user=".$userID."&amp;opinion=".$_GET["opinion"]."&amp;action=makeFavourite";
  $urlDestroyFavourite = "user.php?user=".$userID."&amp;opinion=".$_GET["opinion"]."&amp;action=destroyFavourite";
  require($this->dir."userInfo.php");
  unset($userClass);
  unset($lng);
 }

 public function userListAll($order,$page,$name = "") {
  $user = new user;
  echo "<table rules=\"groups\">";
  if ($this->owner->id) {
   $res = $user->listAllLogged($order,$page,$name);
   $this->userListAllHeadLogged();
   while($user = mysql_fetch_object($res)) {
    $user->login = $this->dateFormatShort($user->login);
    $this->userListAllItemLogged($user);
    $pageMax = ($user->pageHelp-$user->pageHelp%$user->pageLimit)/$user->pageLimit; 
   }  
  }
  else {   
   $res = $user->listAll($order,$page,$name);
   $this->userListAllHead();
   while($user = mysql_fetch_object($res)) {
    $user->login = $this->dateFormatShort($user->login);
    $this->userListAllItem($user);
    $pageMax = ($user->pageHelp-$user->pageHelp%$user->pageLimit)/$user->pageLimit; 
   }
  }
  echo "</table>";
  $this->page("user.php?action=list&amp;order=".$_GET[order]."&amp;name=".$_GET[name],$page,$pageMax);
 }
 
 public function userListAllHead() {
  $lng = new language;
  require($this->dir."userListAllHead.php");
  unset($lng);
 }
 
 public function userListAllHeadLogged() {
  $lng = new language;
  require($this->dir."userListAllHeadLogged.php");
  unset($lng);
 } 
 
 public function userListAllItem($user) {
  require($this->dir."userListAllItem.php");
 }

 public function userListAllItemLogged($user) {
  require($this->dir."userListAllItemLogged.php");
 }

 
 public function userListTop($limit = 20) {
  $userClass = new user;
  $res = $userClass->topList($limit);
  unset($userClass);
  $lng = new language;
  echo "<div id=\"userList\"><h3>$lng->topUser</h3>";
  while($user = mysql_fetch_object($res)) {
   require($this->dir."userListItem.php");  
  }
  echo "<hr class=\"clear\"></div>";
 }
 
 public function userSearchForm($action = "user.php?action=search") {
  $lng = new language;
  require($this->dir."userSearchForm.php");
  unset($lng);
 }
}
?>
