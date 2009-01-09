<?php
/**
 * @package		templateReader
 * @author 		Jan Papousek
 * @copyright 	Internetovy ctenarsky denik
 * @link		http://ctenari.cz
 */

/**
 * 				Trida zobrazujici tabulku vyhledanych vlastni tvorby.
 * @package		templateReader
 */
class WritingListSearch extends Table {
	
	public function __construct() {
		parent::__construct();
		$orderTitle = "title";
		$orderUser = "userName";
		$orderDate = "date";
		switch(Page::get("order")) {
			case $orderTitle:
				$orderTitle .= " DESC";
				break;
			case $orderUser:
				$orderUser .= " DESC";
				break;
			case $orderDate:
				$orderDate .= " DESC";
				break;
		}
		$this->setHead(array(
			new A(
				Lng::WRITING_TITLE,
				"writing.php?action=search&amp;searchWord=".Page::get("searchWord")."&amp;order=$orderTitle",
				Lng::ORDER
			),
			new A(
				Lng::USER_NAME,
				"writing.php?action=search&amp;searchWord=".Page::get("searchWord")."&amp;order=$orderUser",
				Lng::ORDER
			),
			new A(
				Lng::DATE_INSERT,
				"writing.php?action=search&amp;searchWord=".Page::get("searchWord")."&amp;order=$orderDate",
				Lng::ORDER
			)
		));
		$res = Search::search("writing",Page::get("searchWord"),Page::get("order"),Page::get("page"));
		while($comp = mysql_fetch_object($res)) {
			$this->addRow(array(
				new A($comp->name,"competition.php?action=readOne&amp;comp=".$comp->id),
				new A($comp->userName,"user.php?user=".$comp->userID),
				new String(String::dateFormatShort($comp->date)),
				new String(String::dateFormatFromShortToShort($comp->expiration))
			));
		}
	}
}
?>