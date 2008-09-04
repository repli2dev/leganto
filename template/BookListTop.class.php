<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/

/**
* Tato trida slouzi jako predek pro zobrazeni tabulky s naposled pridanymi knihami.
* @package reader
*/
class BookListTop extends BookList {
	
	/**
	 * @var int ID uzivatele, od ktereho se maji zobrazit naposled pridane knihy (pokud je nastaveno na 0, zobrazi naposledy pridane knihy v ramci vsech uzivatelu).
	 */
	protected $user = 0;
	
	public function __construct($user = 0) {
		$this->data = Book::top($user);
		$this->switcherRead = FALSE;
		parent::__construct();
		$this->setID("topBook");
		$this->caption = Lng::TOP_BOOK;
	}
}
?>