<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Tabulka s vyhledanymi uzivateli.
* @package readerTemplate
*/
class UserTable extends Table {
	
	public function __construct() {
		parent::__construct();
		$nameOrder = "name";
		$recommendOrder = "recommend DESC";
		$loginOrder = "login";
		switch(Page::get("order")) {
			case $nameOrder:
				$nameOrder .= " DESC";
				break;
			case $recommendOrder:
				$recommendOrder = "recommend";
				break;
			case $loginOrder:
				$loginOrder .= " DESC";
				break;
		}
		$this->setHead(array(
			new A(Lng::USER_NAME,"user.php?action=search&amp;searchWord=".Page::get("searchWord")."&amp;order=$nameOrder"),
			new A(Lng::CARMA,"user.php?action=search&amp;searchWord=".Page::get("searchWord")."&amp;order=$recommendOrder"),
			new A(Lng::LAST_LOGGED,"user.php?action=search&amp;searchWord=".Page::get("searchWord")."&amp;order=$loginOrder")
		));
		$res = User::listAll(Page::get("order"),Page::get("page"),Page::get("searchWord"));
		while($user = mysql_fetch_object($res)) {
			$this->addRow(array(
				new A($user->name,"user.php?user=".$user->id),
				new String($user->recommend),
				new String(String::dateFormatShort($user->login))
			));
		}
	}
}
?>