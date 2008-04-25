<?php
class rss extends reader {
 public $limit;

 public function __construct() {
  parent::__construct();
  $this->limit = 10;
 }

 public function favouriteGroup($usID) {
  $opinion = new opinion;
  $recommend = new recommend;
  $con = "$opinion->table.user IN (SELECT recommend FROM $recommend->table WHERE user = $usID)";
  $sql = $this->makeQuestion($con);
  $last = $this->last($con);
  $res = mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
  $lng = new language;
  $user = new user;
  $user = $user->getInfo($usID);
  $title = "$user->name - $lng->userFavourite";
  unset($lng);
  $this->makeRSS($res,$last,$title);  
 }
 
 public function last($condition) {
  $opinion = new opinion;
  $sql = "SELECT MAX(date) AS last FROM $opinion->table WHERE $condition";
  $res = mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
  $record = mysql_fetch_object($res);
  return $record->last;  
 }

 public function makeQuestion($condition) {
  $book = new book;
  $writer = new writer;
  $comment = new comment;
  $opinion = new opinion;
  $sql = "
   SELECT
    $book->table.title AS bookTitle,
    $writer->table.name AS writerName,
    COUNT($comment->table.id) AS commentCount,
    ROUND(AVG($opinion->table.rating)) AS rating,
    $opinion->table.id AS opinionID,
    $opinion->table.content AS opinion,
    $opinion->table.date AS opinionDate,	
    $opinion->table.user
   FROM $book->table
   LEFT JOIN $writer->table ON $book->table.writer = $writer->table.id
   LEFT JOIN $comment->table ON $book->table.id = $comment->table.book
   LEFT JOIN $opinion->table ON $book->table.id = $opinion->table.book
	WHERE   
   $condition
   GROUP BY $book->table.id
   ORDER BY opinionDate DESC
   LIMIT 0,$this->limit
  ";
  unset($book);
  unset($writer);
  unset($opinion);
  unset($comment); 
  return $sql;
 }
 
 public function makeRSS($res,$last,$title) {
  header('content-type: text/xml');
  
?>
<rss version="2.0">
  <channel>
    <title><?php echo "$this->webName - $title" ?></title>
    <link>http://<?php echo $this->webUrl; ?></link>
    <description>
    <?php echo $this->webDescription ?>
    </description>
    <language>cs</language>
    <copyright>Copyright 2007, ctenari.cz</copyright>
    <managingEditor>papi@ctenari.cz</managingEditor>
    <webMaster><?php echo $config->webEmail; ?></webMaster>
    <lastBuildDate><?php echo $last; ?></lastBuildDate>
    <docs>http://backend.userland.com/rss092</docs>
<?php
  while($op = mysql_fetch_object($res)) {
  	$op->opinion = $this->getFirstParagraph($op->opinion);
?>
    <item>
     <title><?php echo "$op->bookTitle - $op->writerName - ".$op->rating."*" ?></title>
     <link>http://ctenari.cz/user.php?user=<?php echo $op->user ?>&amp;opinion=<?php echo $op->opinionID ?>&amp;action=opinion</link>
     <pubDate><?php echo $op->opinionDate ?></pubDate>
     <description><?php echo $op->opinion ?></description>
    </item>
<?php  
  }
?>
  </channel>
</rss>
<?php
 }
 
 public function user($usID) {
  $opinion = new opinion;
  $con = "$opinion->table.user = $usID";
  unset($opinion);
  $sql = $this->makeQuestion($con);
  $last = $this->last($con);
  $res = mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
  $lng = new language;
  $user = new user;
  $user = $user->getInfo($usID);
  $title = "$user->name";
  unset($lng);
  $this->makeRSS($res,$last,$title);
 }
}
?>