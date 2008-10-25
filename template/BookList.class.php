<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/

/**
* Tato trida slouzi jako predek pro zobrazovane tabulky s knihami.
* @package reader
*/
abstract class BookList extends Table {
	
	/**
	 * @var mysql_result
	 */
	protected $data;

	/**
	 * @var boolean Zapne/Vypne zobrazovani poctu precteni.
	 */
	protected $switcherRead = TRUE;
	
	/**
	 * Vrati data, ktera se vlozi do zobrazene sablony.
	 * @return mysql_result
	 */
	protected function getData() {
		return $this->data;
	}
	
	/**
	 * Konstruktor
	 * return void
	 */
	public function __construct() {
		parent::__construct();
		$res = $this->getData();
		while ($book = mysql_fetch_object($res)) {
			$item = array(
				new A($book->title,"book.php?book=".$book->id),
				new A($book->writerName,"search.php?searchWord=".$book->writerName."&amp;column=writer&amp;order=writer"),
				new Img("image/rating_".$book->rating.".png","")
			);
			if ($this->switcherRead) {
				$item[] = $book->countRead;
			}
			$this->addRow($item);
		}
	}
}
?>