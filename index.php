<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/

error_reporting(E_ALL ^ E_NOTICE);

require_once("include/config.php");


/**
* Index webu.
* @package reader
*/
class IndexPage extends CommonPage {
	
	public function __construct() {
		parent::__construct();
		$this->addContent(new Introduction());
		$this->addContent(new BookListTop());
		$this->addContent(new BookListLast());
		$this->addContent(new UserBoxTop());
		$this->addRightColumn(new CompetitionLastBox());
		$this->addRightColumn(new WritingLastBox());
	}
}

$page = new IndexPage();
$page->view();
?>