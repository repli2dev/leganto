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
		header("Content-Type: text/html; charset=utf-8");
		$book = new BookWriter($argv['bookTitle']);
		return $book->get();
	}
	/**
	 * Vytvori ikonku uzivatele
	 */
	function userIcon($argv){
		//ziskani udaju
		//jmeno uzivatele
		new User;
		$row = mysql_fetch_object(User::userName($this->get('id')));
		$userName = $row->name;
		//posledni 2 knihy
		$res = Book::last($this->get('id'));
		$row1 = mysql_fetch_object($res);
		$row2 = mysql_fetch_object($res);

		//vykresleni obrazku
		header('Content-Type: image/png');
		$image = imagecreatefrompng("image/propag_normal.png");
		$font = "image/DejaVuSans.ttf";
		//barvy
		$black = imagecolorallocate($image,0,0,0);
		$brown = imagecolorallocate($image,153,102,51);
		//vypsani jmena ctenare
		imagettftext($image, 10, 0, 27, 29, $black, $font, $userName);
		//prvni kniha
		imagettftext($image, 8, 0, 4, 46, $brown, $font, wordwrap($row1->title,21));
		$size = imagettfbbox(8,0, $font, wordwrap($row1->title,21));
		$next_pos = abs($size[3] - $size[5])+46;
		imagettftext($image, 8, 0, 4, $next_pos+2, $black, $font, $row1->writerName);
		$size = imagettfbbox(8,0, $font, $row1->writerName);
		$next_pos = $next_pos + abs($size[3] - $size[5])+10;
		//druha kniha
		imagettftext($image, 8, 0, 4, $next_pos, $brown, $font, wordwrap($row2->title,21));
		$size = imagettfbbox(8,0, $font, wordwrap($row2->title,21));
		$next_pos = $next_pos + abs($size[3] - $size[5]);
		imagettftext($image, 8, 0, 4, $next_pos+2, $black, $font, $row2->writerName);
		//vykresleni a zniceni objektu
		imagepng($image);
		imagedestroy($image);
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