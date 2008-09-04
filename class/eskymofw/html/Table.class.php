<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Trida slouzici pro praci s HTML tagem <table></table>.
* @example doc_example/Table.phps
*/
class Table extends HTMLTag {
	
	/**
	 * @var string Nazev tabulky.
	 */
	public $caption = "";
	
	/**
	 * @var mixed Polozky v hlavicce tabulky.
	 */
	private $head = array();
	
	/**
	 * @var mixed Polozky v paticce tabulky.
	 */
	private $foot = array();
	
	/**
	 * @var mixed Telo tabulky.
	 */
	private $body;
	
	/**
	 * @var int Pocet sloupcu tabulky. 
	 */
	private $numColumns = 0;
	
	/**
	 * Konstruktor.
	 * @return void
	 */
	public function __construct() {
		$this->setPair();
		$this->setTag("table");
		$this->body = new HTMLTag();
		$this->body->setPair();
		$this->body->setTag("tbody");
	}
	
	/**
	 * Vlozi do tabulky radek.
	 * @param mixed Hodnoty bunek v radku. Vrati FALSE, pokud uz je urcen pocet sloupcu a neshoduje se s vkladanych hodnot. 
	 */
	public function addRow($row) {
		try {
			if ((count($row) != $this->numColumns) && ($this->numColumns != 0)) {
				throw new Error(Language::WRONG_NUMBER_OF_TABLE_COLUMNS);
			}			
			$this->numColumns = 0;
			$tr = new HTMLTag;
			$tr->setPair();
			$tr->setTag("tr");
			foreach($row as $item) {
				$td = new HTMLTag;
				$td->setPair();
				$td->setTag("td");
				$td->addValue($item);
				$tr->addValue($td);
				unset($td);
			}
			$this->body->addValue($tr);
			unset($tr);
		}
		catch(Error $e) {
			$e->scream();
		}
	}
	
	/**
	 * Vrati pocet sloupcu tabulky.
	 * @return int
	 */
	public function getNumColumns() {
		return $this->numColumns;
	}
	
	/**
	 * Nastavi paticku stranky. Vrati FALSE, pokud uz je urcen pocet sloupcu a neshoduje se s poctem polozek v paticce.
	 * @param mixed Polozky v paticce.
	 * @return boolean
	 */
	public function setFoot($foot) {
		if ((count($foot) != $this->numColumns) && ($this->numColumns != 0)) {
			return FALSE;
		}	
		$this->foot = array();
		$this->numColumns = 0;
		foreach($foot as $item) {
			$this->foot[] = $item;
			$this->numColumns++;		 	
		}
		return TRUE;	
	}
	
	/**
	 * Nastavi hlavicku tabulky. Vrati FALSE, pokud uz je urcen pocet sloupcu a neshoduje se s poctem polozek v hlavicce.
	 * @param mixed Nazvy sloupcu tabulky.
	 * @return boolean
	 */
	public function setHead($head) {
		if ((count($head) != $this->numColumns) && ($this->numColumns != 0)) {
			return FALSE;
		}
		$this->head = array();
		$this->numColumns = 0;
		foreach($head as $item) {
			$this->head[] = $item;
			$this->numColumns++;		 	
		}
		return TRUE;
	}

	/**
	 * Vytiskne tabulku.
	 * @retrun void
	 */
	public function view() {
		if ($this->caption) {
			$caption = new HTMLTag;
			$caption->setTag("caption");
			$caption->setPair();
			$caption->addValue($this->caption);
			$this->addValue($caption);
			unset($caption);
		}
		// V pripade, ze existuje hlavicka tabulky, naplni promennou $head, ktera se potom jako hlavicka vytiskne.
		if (count($this->head) != 0) {
			$head = new HTMLTag;
			$head->setPair();
			$head->setTag("thead");
			$tr = new HTMLTag;
			$tr->setPair();
			$tr->setTag("tr");
			foreach($this->head as $item) {
				$th = new HTMLTag;
				$th->setPair();
				$th->setTag("th");
				$th->addValue($item);
				$tr->addValue($th);
				unset($th);
			}
			$head->addValue($tr);
			unset($tr);
			$this->addValue($head);
		}
		$this->addValue($this->body);
		// Pokud existuje paticka tabulky, naplni promennou $foot, ktera se pak vytiskne. 
		if (count($this->foot) != 0) {
			$foot = new HTMLTag;
			$foot->setPair();
			$foot->setTag("tfoot");
			$tr = new HTMLTag;
			$tr->setPair();
			$tr->setTag("tr");
			foreach($this->foot as $item) {
				$td = new HTMLTag;
				$td->setPair;
				$td->setTag("th");
				$td->addValue($item);
				$td->addValue($td);
				unset($td);
			}
			$foot->adValue($tr);
			unset($tr);
			$this->addValue($foot);
		}
		parent::view();
	}
}

?>