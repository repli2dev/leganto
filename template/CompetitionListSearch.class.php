<?php
/**
 * @package		templateReader
 * @author 		Jan Papousek
 * @copyright 	Internetovy ctenarsky denik
 * @link		http://ctenari.cz
 */

/**
 * 				Trida zobrazujici tabulku vyhledanych soutezi.
 * @package		templateReader
 */
class CompetitionListSearch extends Table {
	
	public function __construct() {
		parent::__construct();
		$orderName = "name";
		$orderUser = "userName";
		$orderDate = "date";
		$orderExp = "expiration";
		switch(Page::get("order")) {
			case $orderName:
				$orderName .= " DESC";
				break;
			case $orderUser:
				$orderUser .= " DESC";
				break;
			case $orderDate:
				$orderDate .= " DESC";
				break;
			case $orderExp:
				$orderExp .= " DESC";
				break;
		}
		$this->setHead(array(
			new A(
				Lng::COMPETITION_NAME,
				"competition.php?action=search&amp;searchWord=".Page::get("searchWord")."&amp;order=$orderName",
				Lng::ORDER
			),
			new A(
				Lng::USER_NAME,
				"competition.php?action=search&amp;searchWord=".Page::get("searchWord")."&amp;order=$orderUser",
				Lng::ORDER
			),
			new A(
				Lng::DATE_INSERT,
				"competition.php?action=search&amp;searchWord=".Page::get("searchWord")."&amp;order=$orderDate",
				Lng::ORDER
			),
			new A(
				Lng::EXPIRATION_DATE,
				"competition.php?action=search&amp;searchWord=".Page::get("searchWord")."&amp;order=$orderExp",
				Lng::ORDER
			)
		));
		$res = Search::search("competition",Page::get("searchWord"),Page::get("order"),Page::get("page"));
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