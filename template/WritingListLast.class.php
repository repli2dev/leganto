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
class WritingListLast extends Div {
	
	/**
	 * @var int ID uzivatele, od ktereho se maji zobrazit nejnovejsi zapisky (pokud je nastaveno na 0 zobrazi se od vsech uzivatelu).
	 */
	protected $user = 0;
	
	/**
	 * @var boolean Pokud je nastaveno na FALSE, box se nezobrazi.
	 */
	private $switcherView = FALSE; 

	public function __construct($user = 0) {
		parent::__construct();
		$this->setClass("column");
		$this->addValue(new H(2,Lng::LATEST_WRITING));
		$res = Writing::last($user);
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
			$this->switcherView = TRUE;
		}
		$this->addValue($ul);
		unset($ul);
	}

	public function getValue() {
		if ($this->switcherView) {
			return parent::getValue();
		}
	}
}
?>
