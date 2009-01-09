<?php
/**
* @package		readerTemplate
* @author		Jan Papousek
* @copyright	Jan Papousek 2007
* @link			http://ctenari.cz
*/
/**
*			Box s klicovymi slovy k dane polozce, pripadne s nejcasteji pouzivanymi klicovymi slovy.
* @package	readerTemplate
*/
class TagBox extends Div {
	
	/**
	 *			Box s klicovymi slovy. Pokud je vyplnena polozka, jedna se o box s klicovymi slovy k dane polozce.
	 *
	 * @param	int		ID polozky 
	 * @param 	string	Typ polozky
	 */
	public function __construct($type = "book",$item = 0) {
		parent::__construct();
		$this->setClass("column");
		switch($type) {
			default:
				$action = "search.php?";
				break;
			case "writing":
				$action = "writing.php?action=search&amp;";
				break;
			case "competition":
				$action = "competition.php?action=search&amp;";
				break;				
		}
		$this->addValue(new H("2",Lng::TAGS));
		if (!$item) {
			$res = Tag::getListTop($type);
		}
		else {
			$res = Tag::getByType($item, $type);
		}
		$p = new P;
		while($tag = mysql_fetch_object($res)) {
			$a = new A(
				$tag->name,
				$action."searchWord=".$tag->name
			);
			$a->setClass("tag".$tag->size);
			$p->addValue($a);
			unset($a);
		}
		$this->addValue($p);
		unset($p);
	}
}
?>