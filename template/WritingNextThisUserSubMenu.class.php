<?php
/**
* @package readerTemplate
* @author Jan Drábek
* @copyright Jan Drábek 2008
* @link http://ctenari.cz
*/
/**
* Naposledy pridane zapisky
* @package readerTemplate
*/
class WritingNextThisUserSubMenu extends Div {
	
	/**
	 * @var int ID uzivatele, od ktereho se maji zobrazit nejnovejsi zapisky (pokud je nastaveno na 0 zobrazi se od vsech uzivatelu).
	 */
	protected $user = 0;
	
	public function __construct($id) {
		parent::__construct();
		$user = Writing::entryOwner($id);
		$this->setClass("column");
		$this->addValue(new H(2,Lng::NEXT_THIS_USER));
		$res = Writing::last($user,$id);
		$ul = new Ul();
		while ($record = mysql_fetch_object($res)) {
			if(!empty($record->link)){
				$url = $record->link;
			} else {
				$url = "writing.php?action=readOne&amp;id=".$record->id;
			}
			$ul->addLi(new A(
				$record->title,
				$url
			));
		}
		$this->addValue($ul);
		unset($ul);
	}
}
?>