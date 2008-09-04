<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/

require_once("include/config.php");

/**
 * Stranka s vyhledavanim knih.
 * @package reader
 */
class SearchPage extends CommonPage {
	
	public function __construct() {
		parent::__construct();
		$this->setTitle(Lng::SEARCH." - ".Page::get("searchWord"));
		$this->addContent(new H(2,Lng::SEARCH_VALUE.": \"".Page::get("searchWord")."\"",TRUE));
		$this->addContent(new BookListSearch());
		$this->addContent(new Paging(__FILE__));
	}
}

$page = new SearchPage();
$page->view();
?>