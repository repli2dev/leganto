<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Trida slouzici jako predek pro vsechny HTML tagy.
*/
class HTMLTag extends Object {

	/**
	* @var Object Pole objektu (hodnot tagu).
	*/
	protected $value = array();	

	/**
	* @var int Pocet objektu v poli hodnot Tag::$value.
	*/
	private $numVal = 0;
	
	/**
	* @var boolean Parovy (TRUE) / Neparovy tag (FALSE).
	*/
	protected $pair = FALSE;	

	/**
	* @var string Nazev tagu.
	*/
	protected $tag;
	
	/**
	* @var array Pole parametru HTML tagu.
	*/	
	protected $atribut = array();
	
	/**
	* @var array Pole udalosti (javascript)
	*/
	protected $event = array();
	
	/**
	* Konstruktor.
	* @param Object
	* @return void
	*/
	public function __construct(&$value = NULL) {
		parent :: __construct();
		if ($value) {
			$this->addValue($value);
		}
	}
	
	/**
	* Prida objekt od pole hodnot Tag::$value.
	* @see Tag::$value
	* @param Object
	* @return int Poradi objektu v poli hodnot. 
	*/		
	public function addValue(&$object) {
		if (getType($object) != "object") {
			$object = new String($object);
		}
		$this->numVal++;
		$this->value[$this->numVal] = $object;
		return $this->numVal;
	}		
	
	/**
	* Prida atribut do pole atributu Tag::$atribut.
	* @param string Nazev atributu.
	* @param string Hodnota atributu.
	* @return void
	*/
	public function addAtribut($name,$value) {
		$this->atribut[$name] = $value;
	}	
	
	/**
	* Vrati hodnotu atributu.
	* @param string Nazev atributu.
	* @return string
	*/	
	public function getAtribut($name) {
		return $this->atribut[$name];
	}
	
	/**
	* Nastavi ID tagu.
	* @param string ID.
	* @return void
	*/	
	public function setID($id) {
		$this->addAtribut("id",$id);
	}	

	/**
	* Vrati ID.
	* @return string
	*/	
	public function getID() {
		return $this->getAtribut("id");
	}

	/**
	* Nastavi atribut class.
	* @param string
	* @return void
	*/
	public function setClass($class) {
		$this->addAtribut("class",$class);
	}
	
	/**
	* Vrati class.
	* @return string
	*/
	public function getClass() {
		return $this->getAtribut("class");
	}

	/**
	* Nastavi Page::$pair.
	* @param boolean
	* @return void 
	*/
	public function setPair($pair = TRUE) {
		$this->pair = $pair;
	}
	
	/**
	* Vrati Page::$pair.
	* @return boolean
	*/
	public function getPair() {
		return $this->pair;
	}

	/**
	* Prida udalost do pole Tag::event.
	* @param string Nazev udalosti.
	* @param string Odezva na udalost
	* @return void
	*/
	public function addEvent($event,$response) {
		$this->event[$event] = $response;
		Page::addJsFile($response);
	}	
		
	/**
	* Vrati odezvu na urcitou udalost.
	* @param string Nazev udalosti.
	* @return string Odezva na udalost.
	*/	
	public function getEvent($event) {
		return $this->event($event);
	}
	
	/**
	 * Cislo 
	 * @param unknown_type $num
	 */
	public function getValue($num) {
		 
	}
	
	/**
	* Nastavi hodnotu znacky Tag::$tag.
	* @param string Znacka tagu.
	* @return void
	*/	
	public function setTag($tag) {
		$this->tag = $tag;
	}
	
	/**
	* Vrati hodnotu Tag::$tag.
	* @return string
	*/
	public function getTag() {
		return $this->tag;
	}
	
	/**
	 * Vyprazni obsah tagu
	 * @return void
	 */
	public function clean() {
		$this->value = array();
	}
	
	/**
	* Vytiskne tag.
	* @return void
	*/
	public function view() {
		$evt = "";
		foreach ($this->event AS $key => $value) {
			$evt .= " $key = \"$value\"";
		}
		$atribut = "";
		foreach ($this->atribut AS $key => $value) {
			$atribut .= " $key=\"$value\"";
		}
		if ($this->pair) {
			echo "\n<" . $this->tag . $atribut . $evt . ">";
			foreach($this->value AS $item) {
				$item->view();
			}
			echo "</$this->tag>";
		}
		else {
			echo "<$this->tag $atribut $evt />\n";
		}
	}	
}
?>
