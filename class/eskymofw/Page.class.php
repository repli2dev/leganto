<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Trida slouzici pro obsluhu stranky.
*/
class Page extends Object {

	/**
	* @var array Obraz superglobalniho pole $_GET[].
	*/
	private static $get = array();
	
	/**
	* @var array Obraz superglobalniho pole $_POST[].
	*/
	private static $post = array();

	/**
	 * @var string Kodovani zobrazovanych stranek.
	 */
	const CHARSET = "utf-8";
	
	const DIR_JS = "javascript/";
		
	const DIR_CSS = "css/";
	
	/**
	* @var boolean Zapne (Vypne) sessions.
	*/	
	const SWITCHER_SESSION = TRUE;

	/**
	* @var boolean Zapne/Vypne praci s MySQL
	*/
	const SWITCHER_MYSQL = TRUE;

	
	/**
	 * @var boolean Zapne/Vypne hlaseni vyjimek.
	 */
	const SWITCHER_SYSTEM_MESSAGE = TRUE;
	
	/**
	* @var array Obraz superglobalniho pole $_SESSION[]
	*/
	private static $session = array();

	/**
	* @var string Titulek stranky.
	*/
	private $title;

	private static $styleSheet = array();
	
	private static $numCSS;
	
	private static $numJS;
	
	private static $externJS = array();

	/**
	* Promenna nesouci prvky stranky.
	*/
	private $value = array();

	private static $systemMessage = array();
	
	/**
	* Konstruktor - nacte superglobalni pole do atributu Page::$get, Page::$post, Page::$session (kontroluje se jejich obsah).
	* @see Page::$get
	* @see Page::$post
	* @see Page::$session
	*/
	public function __construct() {
		if (self::SWITCHER_SESSION) {
			session_start();
			$this->loadSession();
		}
		$this->loadGet();
		$this->loadPost();
		if (self::SWITCHER_MYSQL) {		
			MySQL::connect();
		}
		parent::__construct();
	}
	
	/**
	* Vrati danou polozku z atributu self :: $get.
	* @see Page::$get
	* @param string
	* @return array_item
	*/
	public function get($key = NULL) {
		if ($key) {
			return self::$get[$key];
		}
		else {
			return self::$get; 
		}
	}
	
	/**
	* Kontroluje zda se v retezci nenachazi podezrele veci (nahradi je)
	* @param string
	* @return string
	*/	
	public static function control($s) {
		/* TOTO NENI HOTOVE --------------------------------------- */
//TODO: Dodelat kontrolu v metode Page::control().
		return $s;
	}

	/**
	* Nahraje pole $_GET[] do atributu Page::$get.
	* @see Page::$get
	* @return void
	*/	
	private function loadGet() {
		foreach ($_GET AS $key => $item) {
			self::setGet($key,$item);
		}
	}
	
	/**
	* Nahraje pole $_POST[] do atributu Page::$get.
	* @see Page::$post
	* @return void
	*/
	private function loadPost() {
		foreach ($_POST AS $key => $item) {
			self::setPost($key,$item);
		}
	}
	
	/**
	* Nahraje pole $_SESSION[] do atributu Page::$session[].
	* @see Page::$session
	* @return void
	*/
	public function loadSession() {
		foreach ($_SESSION AS $key => $item) {
			self::setSession($key,$item);
		}
		
	}
	
	/**
	* Znovu nahraje superglobalni promenne.
	* @return void
	*/
	public static function reload() {
		self::loadPost();
		self::loadGet();
		self::loadSession();
	}

	/**
	* Nastavi danou polozku atributu Page::$get na danou hodnotu.
	* @see Page::$get
	* @param string Nazev polozky.
	* @param array_item Hodnota polozky.
	* @return void
	*/
	public static function setGet($key,$value) {
		self::$get[$key] = self :: control($value);
	}

	/**
	* Vrati danou polozku z atributu Page::$post.
	* @see Page::$post
	* @param string
	* @return array_item
	*/
	public static function post($key = NULL) {
		if ($key) {
			return self::$post[$key];
		}
		else {
			return self::$post;
		}
	}
	
	/**
	* Nastavi danou polozku atributu Page::$post na danou hodnotu.
	* @see Page::$post
	* @param string Nazev polozky.
	* @param array_item Hodnota polozky.
	* @return void
	*/
	public static function setPost($key,$value) {
		self::$post[$key] = self :: control($value);
	}
	
	/**
	* Vrati danou polozku z atributu Page::$session.
	* @see Page::$session
	* @param string
	* @return array_item
	*/
	public function session($key = NULL) {
		if ($key) {
			return self::$session[$key];
		}
		else {
			return self::$session;
		}
	}	

	/**
	* Nastavi danou polozku atributu Page::$session na danou hodnotu.
	* @see Page::$session
	* @param string Nazev polozky
	* @param array_item Hodnota polozky
	* @return void
	*/		
	public static function setSession($key,$value) {
		self::$session[$key] = self::control($value);
		$_SESSION[$key] = self::session($key);
	}

	/**
	* Zmeni titulek stranky Page::$title.
	* @param string
	* @return void
	*/
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	* Vrati titulek stranky Page::$title.
	* @return string
	*/
	public function getTitle() {
		return $this->title;
	}

	/**
	* Prida objekt od pole hodnot Page::$value.
	* @see Page::$value
	* @param Object
	* @return int Poradi objektu v poli hodnot. 
	*/		
	public function addValue($object) {
		$this->numVal++;
		$this->value[$this->numVal] = $object;
		return $this->numVal;
	}

	/**
	* Prida JS soubor do pole externich souboru Page::$externFile na zaklade hlavicky fce.
	* @param string Hlavicka JS fce.
	* @return int Pocet externich JS souboru. 
	*/
	public static function addJsFile($fun) {
		$fn = split("\(",$fun);
		$fn = $fn[0];
		$help = FALSE;
		foreach (Page::$externJS AS $item) {
			if ($item == $fn) {
				$help = TRUE;
			} 
		}
		if (!$help) {
			Page::$numJS++;
			Page::$externJS[self::$numJS] = $fn;	
		}
		return self::$numJS;
	}

	/**
	* Prida CSS soubor do pole externich CSS souboru Page::$styleSheet.
	* @param string Nazev CSS souboru.
	* @return int Pocet externich CSS souboru.
	*/
	public static function addStyleSheet($fn) {
		$help = FALSE;
		foreach (self::$styleSheet AS $item) {
			if ($item == $fn) {
				$help = TRUE;
			}
		}
		if (!$help) {
			self::$numCSS++;
			self::$styleSheet[self::$numCSS] = $fn;
		}
		return self::$numCSS;
	}
	
	/**
	 * Prida na stranku chybovou hlasku.
	 * @param string
	 * @return void
	 */
	public static function addSystemMessage($message) {
		if (getType($message) != "object") {
			$div = new Div();
			$div->setClass("systemMessage");
			$div->addValue(new String($message));
			self::$systemMessage[] = $div;
			unset($div);
		}
	}
	
	/**
	 * Vypise systemove hlasky.
	 * @return void
	 */
	public static function systemMessageView() {
	  	foreach(self::$systemMessage AS $item) {
  			$item->view();
  		}
	}
	
	/**
	* Vytiskne stranku (objekt Page).
	* @return void
	*/
	public function view() {
		echo "
		<?xml version=\"1.0\" encoding=\"UTF-8\"?><!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\">
		<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"cs\" lang=\"cs\">
  			<head>
	 			<script src=\"http://www.google-analytics.com/urchin.js\" type=\"text/javascript\">
    			</script>
    			<script type=\"text/javascript\">
      			_uacct = \"UA-2881824-1\";
      			urchinTracker();
    			</script>
  			<script type=\"text/javascript\">
 				function getImportantFormColumns(formName) {
  					var result = new Array();
  		";  		
		foreach($_SESSION["eskymoImpProp"] AS $key => $impProp) {
			echo "result['$key'] = new Array(";
			$help = "";
			foreach($impProp AS $column) {
				if ($help) {
					$help .= ",";
				}
				$help .= "'$column'";
			}
			echo 	$help;
			echo "	);";
		}
		echo "
					return result[formName];
				}</script>
		";
		$tag = new HTMLTag;
		$tag->setTag("meta");
		$tag->addAtribut("http-equiv","Content-Type");
		$tag->addAtribut("content","text/html; charset=". self::CHARSET);
		$tag->view();
		//Reverse styleSheet array because of overriding with main.css
		self::$styleSheet = array_reverse(self::$styleSheet); 
  		foreach(self::$styleSheet AS $item) {
			$link = new Link("stylesheet", "text/css", self::DIR_CSS . $item);
			$link->view();
  		}
  		unset($link);
  		foreach(Page :: $externJS AS $item) {
  			if (file_exists(self::DIR_JS.$item.".js")) {
  				$file = self::DIR_JS.$item.".js";
  			}
  			else {
  				$file = self::DIR_JS.$item.".php";
  			}
  			$script = new Script("text/javascript", $file);
  			$script->view();
  		}
  		unset($script);
  		if (isset($_SESSION["eskymoSuggest"])) {
			echo "<script type=\"text/javascript\">";
			echo "var list = [ 'Java', 'JavaScript', 'Perl', 'Ruby', 'PHP', 'Python', 'C', 'C++', '.NET', 'MySQL', 'Oracle', 'PostgreSQL'];";
			foreach($_SESSION["eskymoSuggest"] AS $name => $value) {
				$temp = "[ ";
				foreach($value AS $item){
					if ($temp != "[ ") {
						$temp .= ", ";
					}
					$temp .= "'".addSlashes($item)."'";
				}
				$temp .= " ]";
				echo "var ".$name." = function(){new Suggest.Local(\"".$name."\", \"suggest_".$name."\", $temp);};
					window.addEventListener ?
					window.addEventListener('load', ".$name.", false) :
					window.attachEvent('onload', ".$name.");\n\n";
			}
			echo "</script>";  		
  		}
		$title = new HTMLTag(new String($this->title));
  		$title->setPair();
  		$title->setTag("title");
  		$title->view();
  		unset($title);
  		echo "</head>";
  		echo "<body>";
  		if (self::SWITCHER_SYSTEM_MESSAGE) {
			Page::systemMessageView();
  		}		
		foreach($this->value as $item) {
			$item->view();
		}
		echo "</body>";
		echo "</html>";
	} 
}
