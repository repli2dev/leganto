<?php
/**
* Předek všech používaných objektů. Obsahuje obecné deklarace.
* @author Jan Papousek
* @package Class
*/

/**
* Trida
* @package Class
*/
class reader {
/**
* @access public
* @var record
*/
 public $owner;
/**
* @access public
* @var string
*/
 public $imageDirectory;
 private $sqlDB;
 private $sqlServer;
 private $sqlPassword;
 public $sqlPrefix;
 private $sqlUser;
 public $webDescription;
 public $webEmail;
 public $webName;
 public $webUrl;
 

/**
* Inicializuje proměnné.
*/ 
 public function __construct() {
  $this->owner = $_SESSION[logUser];
  $this->imageDirectory = "image/";
  $this->sqlDB = "reader";
  $this->sqlPassword = "mig121";
  $this->sqlPrefix = "reader_";
  $this->sqlServer = "localhost:3306";
  $this->sqlUser = "reader_cz";
  $this->webName = "Čtenáři.cz";
  $this->webDescription = "";
  $this->webEmail = "";
  $this->webUrl = "http://ctenari.cz";
 }

 public function cut($string) {
  $change = array(", " => " ","," => " ");
  $string = strtr($string,$change);
  $string = explode(" ",$string);
  return $string; 
 } 

 public function dateFormat($date) {
  $date = preg_replace("/(\d{4})-0?([1-9]{1,2}0?)-0?([1-9]{1,2}0?) 0?([0-9]{1,2}0?):(\d{2}):(\d{2})/","\\3.&nbsp;\\2.&nbsp;\\1,&nbsp;\\4:\\5",$date);
  return $date;
 }
 
 public function dateFormatShort($date) {
  $date = preg_replace("/(\d{4})-0?([1-9]{1,2}0?)-0?([1-9]{1,2}0?) 0?([0-9]{1,2}0?):(\d{2}):(\d{2})/","\\3.&nbsp;\\2.&nbsp;\\1",$date);
  return $date;
 }
 
 public function dateShakeShort($date) {
 	if (ereg("/(\d{2}).(\d{2}).(\d{4})/",$date,$regs)) {
 		$date = $regs[3]."-".$regs[2]."-".$regs[1];
 		return $date;
 	}
 	else die();
 } 
 
 function getFirstParagraph($var){
  if(preg_match('~<\/p>~ui', $var, $res, PREG_OFFSET_CAPTURE) == true){
   $position = $res[0][1] + 4;
  }elseif(preg_match('~\n.{1}\n~ui', $var, $res, PREG_OFFSET_CAPTURE) == true){
   $position = $res[0][1];
  }else{
   $position = strlen($var);
  }
  $firstPara = substr($var, 0, $position);
  return $firstPara;
 }  

 public function imageRating($rating) {
  return $this->imageDirectory."rating_".$rating.".png"; 
 }

 public function MySQLconnect() {
  $lng = new language;
  mysql_pconnect($this->sqlServer,$this->sqlUser,$this->sqlPassword) or die($lng->failedConnection);
  mysql_select_db($this->sqlDB) or die($lng->noDB);
  mysql_query("SET CHARACTER SET utf8") or die($lng->wrongCharset);
 }
  
 public function utf2ascii($text) {
  $char = array("\xc3\xa1"=>"a","\xc3\xa4"=>"a","\xc4\x8d"=>"c","\xc4\x8f"=>"d","\xc3\xa9"=>"e","\xc4\x9b"=>"e","\xc3\xad"=>"i","\xc4\xbe"=>"l","\xc4\xba"=>"l","\xc5\x88"=>"n","\xc3\xb3"=>"o","\xc3\xb6"=>"o","\xc5\x91"=>"o","\xc3\xb4"=>"o","\xc5\x99"=>"r","\xc5\x95"=>"r","\xc5\xa1"=>"s","\xc5\xa5"=>"t","\xc3\xba"=>"u","\xc5\xaf"=>"u","\xc3\xbc"=>"u","\xc5\xb1"=>"u","\xc3\xbd"=>"y","\xc5\xbe"=>"z","\xc3\x81"=>"A","\xc3\x84"=>"A","\xc4\x8c"=>"C","\xc4\x8e"=>"D","\xc3\x89"=>"E","\xc4\x9a"=>"E","\xc3\x8d"=>"I","\xc4\xbd"=>"L","\xc4\xb9"=>"L","\xc5\x87"=>"N","\xc3\x93"=>"O","\xc3\x96"=>"O","\xc5\x90"=>"O","\xc3\x94"=>"O","\xc5\x98"=>"R","\xc5\x94"=>"R","\xc5\xa0"=>"S","\xc5\xa4"=>"T","\xc3\x9a"=>"U","\xc5\xae"=>"U","\xc3\x9c"=>"U","\xc5\xb0"=>"U","\xc3\x9d"=>"Y","\xc5\xbd"=>"Z",", " => " ", "," => " ");
  return strToLower(strtr($text,$char));
 }
}
?>
