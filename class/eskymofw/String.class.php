<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Trida slouzici pro uchovani dat typu string.
*/
class String extends Object {

	private $switcherTexy = FALSE;
	
	/**
	 * Konstruktor.
	 * @param string Hodnota
	 * @param boolean Zapnuti/Vypnuti Texy! (defaultne vypnuto).
	 */
	public function __construct($value,$texy = FALSE) {
		parent::__construct($value);
		$this->switcherTexy = $texy;
	}
	
	/**
	* Vrati pole slov, ktere byly v puvodnim retezci oddelene carkou.
	* @param string
	* @return mixed
	*/
	public static function cut($string) {
		$change = array(", " => " ","," => " ");
		$string = strtr($string,$change);
		return explode(" ",$string);
	} 

	/**
	 * Prevede strojovy format data do ceskeho (kratkeho).
	 * @param string
	 * @return string
	 */
	public static function dateFormat($date) {
		$date = preg_replace("/(\d{4})-0?([1-9]{1,2}0?)-0?([1-9]{1,2}0?) 0?([0-9]{1,2}0?):(\d{2}):(\d{2})/","\\3.&nbsp;\\2.&nbsp;\\1,&nbsp;\\4:\\5",$date);
		return $date;
	}

	/**
	 * Prevede strojovy format data do ceskeho (kratkeho).
	 * @param string
	 * @return string
	 */
	public static function dateFormatShort($date) {
		$date = preg_replace("/(\d{4})-0?([1-9]{1,2}0?)-0?([1-9]{1,2}0?) 0?([0-9]{1,2}0?):(\d{2}):(\d{2})/","\\3.&nbsp;\\2.&nbsp;\\1",$date);
		return $date;
 	}
	
 	/**
 	 * Vrati nahodny retezec o dane delce.
 	 * @param int Delka vraceneho retezce.
 	 * @return string
 	 */
 	public static function random($length) {
 		$chars = array_merge(range("a","z"), range("A","Z"), range(0,9));
 		$result = "";
 		for($i=0; $i<$length; $i++) {
 			$result = $result.$chars[rand(0,count($chars))];
 		}
 		return $result;
 	}
 	
	/**
	* Vytiskne retezec na obrazovku.
	* @return void
	*/
	public function view() {
		if ($this->switcherTexy) {
			$texy = new Texy();
			$temp = $texy->process($this->getValue());
			echo $this->smiles($temp);
		}
		else {
			echo $this->getValue();
		}
	}

	/**
	* Odstrani diakritiku z retezce
	* @param string Retezec v kodovani UTF-8
	* @return string
	*/
	public static function utf2ascii($string) {
		$char = array("\xc3\xa1"=>"a","\xc3\xa4"=>"a","\xc4\x8d"=>"c","\xc4\x8f"=>"d","\xc3\xa9"=>"e","\xc4\x9b"=>"e","\xc3\xad"=>"i","\xc4\xbe"=>"l","\xc4\xba"=>"l","\xc5\x88"=>"n","\xc3\xb3"=>"o","\xc3\xb6"=>"o","\xc5\x91"=>"o","\xc3\xb4"=>"o","\xc5\x99"=>"r","\xc5\x95"=>"r","\xc5\xa1"=>"s","\xc5\xa5"=>"t","\xc3\xba"=>"u","\xc5\xaf"=>"u","\xc3\xbc"=>"u","\xc5\xb1"=>"u","\xc3\xbd"=>"y","\xc5\xbe"=>"z","\xc3\x81"=>"A","\xc3\x84"=>"A","\xc4\x8c"=>"C","\xc4\x8e"=>"D","\xc3\x89"=>"E","\xc4\x9a"=>"E","\xc3\x8d"=>"I","\xc4\xbd"=>"L","\xc4\xb9"=>"L","\xc5\x87"=>"N","\xc3\x93"=>"O","\xc3\x96"=>"O","\xc5\x90"=>"O","\xc3\x94"=>"O","\xc5\x98"=>"R","\xc5\x94"=>"R","\xc5\xa0"=>"S","\xc5\xa4"=>"T","\xc3\x9a"=>"U","\xc5\xae"=>"U","\xc3\x9c"=>"U","\xc5\xb0"=>"U","\xc3\x9d"=>"Y","\xc5\xbd"=>"Z",", " => " ", "," => " ");
		return strTr($string,$char);
	}

	/**
	* Prevede retezec na mala pismena bez diakritiky.
	* @param string Retezec v kodovani UTF-8
	* @return string
	*/
	public static function utf2lowerAscii($string) {
		return strToLower(self::utf2ascii($string));
	}
	/**
	* Prevede textove smajliky na obrazky
	* @param string Retezec v kodovani UTF-8
	* @return string
	*/
	public static function smiles($string){
		//vytvoreni ukazkovych smajliku vcetne tridy
		$a = new Img("/image/01.png",Lng::S1);
		$a->setClass("smile");
		$b = new Img("/image/02.png",Lng::S2);
		$b->setClass("smile");
		$c = new Img("/image/03.png",Lng::S3);
		$c->setClass("smile");
		$d = new Img("/image/04.png",Lng::S4);
		$d->setClass("smile");
		$e = new Img("/image/05.png",Lng::S5);
		$e->setClass("smile");
		$f = new Img("/image/06.png",Lng::S6);
		$f->setClass("smile");
		$g = new Img("/image/07.png",Lng::S7);
		$g->setClass("smile");
		//jejich náhrada v textu
		$string = str_replace(":-)",$a->getWholeTag(),$string);
		$string = str_replace(":-D",$b->getWholeTag(),$string);
		$string = str_replace(";-)",$c->getWholeTag(),$string);
		$string = str_replace(":-(",$d->getWholeTag(),$string);
		$string = str_replace(":,–(",$e->getWholeTag(),$string);
		$string = str_replace(":-P",$f->getWholeTag(),$string);
		$string = str_replace("&gt;:(",$g->getWholeTag(),$string);
		//navraceni retezcu se smajliky
		return $string;
	}
}
?>
