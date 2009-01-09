<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Naposledy pridane knihy.
* @package readerTemplate
*/
class BookListLast extends BookList {
	
	/**
	 * @var int ID uzivatele, od ktereho se maji zobrazit naposled pridane knihy (pokud je nastaveno na 0, zobrazi naposledy pridane knihy v ramci vsech uzivatelu).
	 */
	protected $user = 0;
	
	public function __construct($user = 0) {
		$this->data = Book::last($user);
		$this->switcherRead = FALSE;
		parent::__construct();
		$this->setID("newBook");
		$this->caption = Lng::LAST_BOOK;
	}
}
?>