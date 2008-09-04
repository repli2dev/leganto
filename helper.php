<?php
//MUST BE THERE
/*header('Content-Type: application/x-www-form-urlencoded; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");*/

require_once("include/config.php");

/**
 * HelperPage - pomocná strana ajaxu
 *
 */
class HelperPage extends BlankPage{
	/**
	 * @var what 
	 * urcuje s cim se bude vypomahat
	 */
	var $what = null;
	
	/**
	 * Konstruktor, pokusi se zobrazit funkci se jmenem v pozadavku what
	 *
	 */
	public function __construct(){
		parent::__construct();
		$this->what = $this->get('what');
		if(method_exists($this,$this->what)){
			//je potreba vytvorit lokalni promennou, jinak funkci nelze zavolat
			$temp = $this->what;
			echo $this->$temp($this->get());
		}
	}
	function writerName($argv){
		$book = new BookWriter($argv['bookTitle']);
		return $book->get();
	}
}

/**
 * BookWriter - funkce, ktera umoznuje vytahnout si to spravne info o knizce na zaklade nazvu knihy
 *
 */
class BookWriter extends MySQLTableBook{
	/**
	 * Autor
	 *
	 * @var string
	 */
	protected $writer;
	
	/**
	 * Install - nic, pouze prepis abstraktní metody
	 *
	 */
	public static function install(){
		
	}
	/**
	 * Konstruktor
	 *
	 */
	public function __construct($bookTitle){
		$sql = "SELECT * FROM ".Book::getTableName()." INNER JOIN ".Writer::getTableName()." ON ".Book::getTableName().".writer = ".Writer::getTableName().".id WHERE reader_book.title='".$bookTitle."'";
		$result = MySQL::query($sql,__FILE__,__LINE__);
		$radku = mysql_num_rows($result);
		$radek = mysql_fetch_array($result);
		if($radku == 1){
			$book = Book::getInfo($radek[0]);
			$this->writer = $book->writerName;
		}
	}
	public function get(){
		return $this->writer;
	}
}


$page = new HelperPage();
$page->view();
?>