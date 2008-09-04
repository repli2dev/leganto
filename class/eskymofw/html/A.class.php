<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Trida slouzici pro praci s HTML tagem <a></a>.
* @example doc_example/A.class.phps
*/
class A extends HTMLTag {

	/**
	 * Konstruktor.
	 * @param Object Predmet odkazu.
	 * @param string Atribut href.
	 * @param string Atribut title.
	 * @return void
	 */	 
	public function __construct($value = NULL, $href = NULL, $title = NULL) {
		parent::__construct($value);
		$this->setTag("a");
		$this->setPair();
		if ($href) {
			$this->href($href);
		}
		elseif ($value) {
			$this->href($value->getValue());
		}
		if ($title) {
			$this->title($title);
		}
		elseif ($value) {
			$this->title($value->getValue());
		}
	}
	
	/**
	 * Nastavi atribut href.
	 * @param string Atribut href.
	 * @return void
	 */
	public function href($href) {
		$this->addAtribut("href",$href);
	}
	
	/**
	 * Nastavi atribut title.
	 * @param string Atribut title.
	 * @return void
	 */
	public function title($title) {
		$this->addAtribut("title",$title);
	}
	
	/**
	 * Vrati hodnotu atributu href.
	 * @return string
	 */
	public function getHref() {
		return $this->getAtribut("href");
	}
	
	/**
	 * Vrati hodnotu atributu title.
	 * @return string
	 */
	public function getTitle() {
		return $this->getAtribut("title");
	}
}
?>
