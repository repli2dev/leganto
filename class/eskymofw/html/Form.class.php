<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Trida slouzici pro praci s FORM tagem <form></form>.
* @example doc_example/Form.class.phps
*/
abstract class Form extends HTMLTag {

	/**
	 * @var int Index naposledy pridaneho fieldsetu v poli hodnot. 
	 */
	protected $lastFildset;
	
	/**
	 * @var mixed Data, ze kterych se plni formularove polozky (Page::getDataToFill).
	 */
	protected $data = array();
	/**
	 * @var string Prefix pro ID pro labely.
	 */
	const LABEL_ID_PREFIX = "eskymoLabel_";
	
	/**
	 * Konstruktor.
	 * @param string Nazev formulare.
	 * @param string Atribut action.
	 * @param string Atribut method.
	 * @param string ID polozky, ke ktere ma byt formular vyplnen.
	 * @param string Atribut enctype.
	 * @return void
	 */	 
	public function __construct($name,$action, $method, $id = NULL, $enctype = NULL) {
		parent::__construct();
		// Kontrola odeslani formulare
		if ($this->isSend()){
			// Zkontroluje radne vyplneni formulare.
			if ($this->controlColumnsValue($name,$method)) {
				$this->execute();
			}
			else {
				$this->getData(NULL,$method);
				$this->renderForm($name,$action,$method,$enctype);
			}
		} else {
			$this->getData($id);
			$this->renderForm($name,$action,$method,$enctype);
		}
	}
	
	/**
	 * vykresli formular
	 * @param string Nazev formulare.
	 * @param string Atribut action.
	 * @param string Atribut method.
	 * @param string Atribut enctype.
	 * @return void
	 */
	public function renderForm($name,$action,$method,$enctype){
		$this->setTag("form");
		$this->setPair();
		$this->addAtribut("name",$name);
		$this->action($action);
		$this->method($method);
		if ($enctype) {
			$this->enctype($enctype);
		}
		//pridani generickeho stylopisu
		Page::addStyleSheet("form.css");
		$this->addAtribut("onSubmit","return checkForm(this)");
		Page::addJsFile("checkForm()");
		if (getType($_SESSION["eskymoImpProp"]) != "array") {
			 $_SESSION["eskymoImpProp"] = array();
		}
		$_SESSION["eskymoImpProp"][$name] = array();
		Page::loadSession();
	}
		
	/**
	 * Nastavi pole dat, ze kterych se naplni formular.
	 * @see Form::$data
	 * @param integer Atribut id.
	 * @param string Atribut method.
	 * @return mixed
	 */
	public function getData($id,$method = NULL){
		if(empty($id)){
			if ($method == "post") {
				$result = Page::post();
			}
			elseif ($method == "get") {
				$result = Page::get();
			}
			
		} elseif($id) {
			if (getType($result = $this->getDataToFill($id)) != "array") {
				$result = array();  
			}
		}
		$this->data = $result;
	}
	
	/**
	 * Nastavi akci, ktera se provede po odeslani formulare
	 * @param string Atribut action.
	 * @return void
	 */
	public function action($action) {
		$this->addAtribut("action",$action);
	}
	
	/**
	 * Nastavi metodu odeslani POST nebo GET
	 * @param string Atribut method.
	 * @return void
	 */
	public function method($method) {
		$this->addAtribut("method",$method);
	}
	
	/**
	 * Zvoli zpusob prenosu dat (ascii vs. binarni vs. url encoded)
	 * @param string Atribut enctype.
	 * @return void
	 */
	public function enctype($enctype) {
		$this->addAtribut("enctype",$enctype);
	}
	
	/**
	 * Přidá fieldset a vrati jeho index v poli hodnot.
	 * @param string Popisek fieldsetu.
	 * @return int
	 */
	public function addFieldset($legend = NULL){
		$this->lastFildset = $this->addValue(new Fieldset($legend));
		return $this->lastFildset;
	}
	
	/**
	 * Prida polozku do naposledy pridaneho fieldsetu.
	 * @param Object Formularova polozka
	 */
	public function addToFieldset($value){
		try {
			if (empty($this->lastFildset)) {
				throw new Error(Language::NO_FIELDSET);
			}
			$this->value[$this->lastFildset]->addValue($value);
		}
		catch (Error $e) {
			$e->scream();
		}
	}
	
	/**
	 * Přidá textove policko
	 * @param boolean Pokud je nastaveno na TRUE, oznaci polozku ve formulari za povinnou.
	 * @param string name
	 * @param string Popiska.
	 * @param string value
	 * @param boolean disabled
	 * @param boolean readonly
	 * @param mixed Zdroj pro naseptavani.
	 * @return void
	 */
	public function addTextInput($important,$name, $label = NULL, $disabled = NULL, $readonly = NULL, $suggest = NULL, $JSaction = NULL){
		if ($important) {
			$this->setImportant($name);
		}
		$p = new P();
		if ($label) {
			$p->addValue(new Label($label,$name));
		}
		$input = new Input($name, $this->data[$name], "text", $disabled, $readonly);
		if(!empty($JSaction)){
			foreach($JSaction AS $key => $value){
				$input->addAtribut($key,"helper_".$key."_".$name."(".$value.")");
			}
		}
		if(isset($suggest) && (getType($suggest) == "array")) {
			$_SESSION["eskymoSuggest"][$name] = $suggest;
			Page::reload();
			Page::addStyleSheet("suggest.css");
			Page::addJsFile("suggest()");
			$input->autoComplete("off");
			$input->setID($name);
			$p->addValue($span = new Span());
			$span->setID("suggest_".$name."");
			$span->setClass("suggest");
		}
		$p->addValue($input);
		$this->addToFieldset($p);
		unset($p);
	}
	
	/**
	 * Přidá pole pro vyber souboru.
	 * @param string name
	 * @param string Popiska.
	 * @param string value
	 * @param boolean disabled
	 * @param boolean readonly
	 * @return void
	 */
	public function addFiletInput($name, $label = NULL, $disabled = NULL, $readonly = NULL){
		$p = new P();
		if ($label) {
			$p->addValue(new Label($label,$name));
		}
		$p->addValue(new Input($name, $this->data[$name], "file", $disabled, $readonly));
		$this->addToFieldset($p);
		unset($p);
	}
	
	/**
	 * Přidá policko pro heslo
	 * @param boolean Pokud je nastaveno na TRUE, oznaci polozku ve formulari za povinnou. 
	 * @param string name
	 * @param string Popiska
	 * @param string value
	 * @param boolean disabled
	 * @param boolean readonly
	 * @return void
	 */
	public function addPasswordInput($important,$name, $label = NULL, $disabled = NULL, $readonly = NULL){
		if ($important) {
			$this->setImportant($name);
		}
		$p = new P();
		if ($label) {
			$p->addValue(new Label($label,$name));
		}
		$p->addValue(new Input($name, $this->data[$name], "password", $disabled, $readonly));
		$this->addToFieldset($p);
		unset($p);
	}
	
	/* Přidá odesílací tlačítko
	 * @param string name
	 * @param string value
	 * @param boolean disabled
	 * @param boolean readonly
	 */
	public function addSubmitButton($name, $value, $disabled = NULL, $readonly = NULL){
		$this->submitButtons[] = $name;
		$this->addToFieldset(new P(new Input($name, $value, "submit", $disabled, $readonly)));
	}
	
	/**
	 * Přidá radio button
	 * @param boolean Pokud je nastaveno na TRUE, oznaci polozku ve formulari za povinnou.
	 * @param string name
	 * @param string Popiska.
	 * @param string value
	 * @param boolean checked
	 * @param boolean disabled
	 * @param boolean readonly
	 */
	public function addRadioInput($important,$name, $label = NULL, $checked= NULL, $disabled = NULL, $readonly = NULL){
		if ($important) {
			$this->setImportant($name);
		}
		$p = new P();
		if ($label) {
			$p->addValue(new Label($label,$name));
		}
		$p->addValue(new Radio($name, $this->data[$name], $checked, $disabled, $readonly));
		$this->addToFieldset($p);
		unset($p);
	}
	
	/**
	 * Přidá checkbox button
	 * @param boolean Pokud je nastaveno na TRUE, oznaci polozku ve formulari za povinnou.
	 * @param string name
	 * @param string Popiska.
	 * @param string value
	 * @param boolean checked
	 * @param boolean disabled
	 * @param boolean readonly
	 */
	public function addCheckboxInput($important,$name, $label = NULL, $checked= NULL, $disabled = NULL, $readonly = NULL){
		if ($important) {
			$this->setImportant($name);
		}
		$p = new P();
		if ($label) {
			$p->addValue(new Label($label,$name));
		}
		$p->addValue(new Checkbox($name, $this->data[$name], $checked, $disabled, $readonly));
		$this->addToFieldset($p);
		unset($p);
	}
	
	/**
	 * Přidá textareu
	 * @param boolean Pokud je nastaveno na TRUE, oznaci polozku ve formulari za povinnou.
	 * @param string name
	 * @param string Popiska.
	 * @param String Zobrazeny text.
	 * @param integer cols
	 * @param integer rows
	 * @param ioolean disabled
	 * @param boolean readonly
	 * @param string wrap
	 */
	public function addTextarea($important,$name, $label = NULL, $cols = NULL, $rows = NULL,$disabled = NULL, $readonly = NULL, $wrap = NULL){
		if ($important) {
			$this->setImportant($name);
		}
		$p = new P();
		if ($label) {
			$p->addValue(new Label($label,$name));
		}
		$p->addValue(new Textarea($name,$this->data[$name],$cols,$rows,$disabled,$readonly,$wrap));
		$this->addToFieldset($p);
		unset($p);
	}

	/**
	 * Přidá select
	 * @param boolean Pokud je nastaveno na TRUE, oznaci polozku ve formulari za povinnou.
	 * @param string name
	 * @param mixed Pole hodnot (options), kde index oznacuje zobrazeny text a jeho hodnota hodnotu.
	 * @param string Popiska.
	 * @param boolean multiple
	 * @param integer size
	 * @param boolean disabled
	 */
	public function addSelect($important,$name, $options = array(), $label = NULL, $multiple = NULL, $size = NULL,$disabled = NULL){
		if ($important) {
			$this->setImportant($name);
		}
		$select = new Select($name, $multiple, $size,$disabled);
		foreach ($options AS $key => $item) {
			$select->addOption($key,$item);
		}
		$p = new P();
		if ($label) {
			$p->addValue(new Label($label,$name));
		}
		$p->addValue($select);
		unset($select);
		$this->addToFieldset($p);
		unset($p);
	}
	
	/**
	 * Nastavi prvek z danym jmenem za povinny.
	 * @param string Jmeno formularoveho prvku.
	 * @return void
	 */
	private function setImportant($name) {
		// Uklada do session nazvy povinnych polozek.
		$_SESSION["eskymoImpProp"][$this->getAtribut("name")][] = $name;
	}
	
	/**
	 * Zkontroluje, zda jsou vyplnene povinne polozky ve formulari.
	 * @param string Nazev formulare.
	 * @param string Pouzita metoda.
	 * @return boolean
	 */
	protected function controlColumnsValue($name,$method) {
		$impColumns = Page::session("eskymoImpProp");
		foreach($impColumns[$name] AS $item) {
			if ($method == "post") {
				if (empty($_POST[$item])) {
					return FALSE;
				}
			}
			else {
				if (empty($_GET[$item])) {
					return FALSE;
				}
			}
		}
		return TRUE;
	}
	
	public function view() {
		if (gettype($_SESSION) == "array") {
			Page::loadSession();
		}
		parent::view();
	}
	
	/**
	 * Zjisti, zda je formular odeslan (vrati TRUE). 
	 * @return boolean
	 */
	 protected abstract function isSend();
	
	/**
	 * Provede akci pri uspesnem odeslani formulare.
	 * @return void
	 */
	protected abstract function execute();
	
	/**
	 * Vrati data k naplneni formulare pred odeslanim.
	 * @param int ID polozky
	 * @return mixed
	 */
	protected abstract function getDataToFill($id);
	
}

/**
* @package eskymoFW
* Trida slouzici pro praci s Fieldset tagem <fieldset></fieldset>.
* @example doc_example/Form.phps
*/
class Fieldset extends HTMLTag {
	
	/**
	 * Konstruktor.
	 * @param string Atribut action.
	 * @param string Atribut legend.
	 * @return void
	 */	 
	public function __construct($legend = NULL) {
		$this->setTag("fieldset");
		$this->setPair();
		if ($legend) {
			$this->legend($legend);
		}
	}
	/**
	 * Vytvori legendu fieldsetu
	 * @param string Atribut legend.
	 * @return void
	 */
	public function legend($legend){
		$this->addValue(new Legend($legend));
	}
}
/**
* @package eskymoFW
* Trida slouzici pro praci s Fieldset tagem <fieldset></fieldset>.
* @example doc_example/Form.phps
*/
class Legend extends HTMLTag {
	
	/**
	 * Konstruktor.
	 * @param Object Attribut value.
	 * @return void
	 */	 
	public function __construct($value = NULL) {
		parent::__construct();
		$this->setTag("legend");
		$this->setPair();
		if ($value) {
			$this->addValue($value);
		}
	}
}

/*
* @package eskymoFW
* Trida slouzici pro praci s input tagem <input />
* @example doc_example/Form.phps
*/
class Input extends HTMLTag {
	
	/**
	 * Konstruktor.
	 * @param Object Attribut Input.
	 * @param String Attribut name.
	 * @param String Attribut type
	 * @param Boolean Attribut disabled
	 * @param Boolean Attribut readonly
	 * @return void
	 */	 
	public function __construct($name = NULL, $value = NULL, $type = NULL, $disabled = NULL, $readonly = NULL) {
		parent::__construct();
		$this->setTag("input");
		if ($value) {
			$this->addAtribut("value", $value);
		}
		if ($type){
			$this->addAtribut("type", $type);
		}
		if ($name){
			$this->addAtribut("name", $name);
		}
		if ($disabled){
			$this->addAtribut("disabled", $disabled);
		}
		if ($readonly){
			$this->addAtribut("readonly", $readonly);
		}
	}
	/**
	 * 
	 * @param unknown_type $value
	 */
	public function autoComplete($value = NULL){
		$this->addAtribut("autocomplete", $value);
	}
	
}
/*
* @package eskymoFW
* Trida slouzici pro praci s input tagem <input /> konkrétně s radio inputem
* @example doc_example/Form.phps
*/
class Radio extends Input {
	
	/**
	 * Konstruktor.
	 * @param string Attribut Input.
	 * @param string Attribut name.
	 * @param boolean Attribut checked
	 * @param boolean Attribut disabled
	 * @param boolean Attribut readonly
	 * @return void
	 */	 
	public function __construct($name = NULL, $value = NULL, $checked = NULL, $disabled = NULL, $readonly = NULL) {
		parent::__construct();
		$this->setTag("input");
		$this->addAtribut("type", "radio");
		if ($value) {
			$this->addAtribut("value", $value->getValue());
		}
		if ($checked){
			$this->addAtribut("checked", "checked");
		}
		if ($name){
			$this->addAtribut("name", $name);
		}
		if ($disabled){
			$this->addAtribut("disabled", $disabled);
		}
		if ($readonly){
			$this->addAtribut("readonly", $readonly);
		}
	}
}

/*
* @package eskymoFW
* Trida slouzici pro praci s input tagem <input />
* @example doc_example/Form.phps
*/
class Checkbox extends Input {
	
	/**
	 * Konstruktor.
	 * @param string Attribut Input.
	 * @param string Attribut name.
	 * @param boolean Attribut checked
	 * @param boolean Attribut disabled
	 * @param boolean Attribut readonly
	 * @return void
	 */	 
	public function __construct($name = NULL, $value = NULL, $checked = NULL, $disabled = NULL, $readonly = NULL) {
		parent::__construct();
		$this->setTag("input");
		$this->addAtribut("type", "checkbox");
		if ($value) {
			$this->addAtribut("value", $value->getValue());
		}
		if ($checked){
			$this->addAtribut("checked", "checked");
		}
		if ($name){
			$this->addAtribut("name", $name);
		}
		if ($disabled){
			$this->addAtribut("disabled", $disabled);
		}
		if ($readonly){
			$this->addAtribut("readonly", $readonly);
		}
	}
}

/*
* @package eskymoFW
* Trida slouzici pro praci s textareou, tagem <textarea></textarea>
* @example doc_example/Form.phps
*/
class Textarea extends HTMLTag {
	
	/**
	 * Konstruktor.
	 * @param string Attribut value.
	 * @param string Attribut name.
	 * @param integer Attribut cols.
	 * @param integer Attribut rows.
	 * @param boolean Attribut disabled.
	 * @param boolean Attribut readonly.
	 * @param string Attribut wrap.
	 * @return void
	 */	 
	public function __construct($name = NULL, $value = NULL, $cols = NULL, $rows = NULL,$disabled = NULL, $readonly = NULL, $wrap = NULL) {
		parent::__construct();
		$this->setTag("textarea");
		$this->setPair();
		if ($value) {
			$this->addValue($value);
		}
		if ($name){
			$this->addAtribut("name", $name);
		}
		if ($cols){
			$this->addAtribut("cols", $cols);
		}
		if ($rows){
			$this->addAtribut("rows", $rows);
		}
		if ($disabled){
			$this->addAtribut("disabled", $disabled);
		}
		if ($readonly){
			$this->addAtribut("readonly", $readonly);
		}
		if ($wrap){
			$this->addAtribut("wrap", $wrap);
		}
	}
}

/*
* @package eskymoFW
* Trida slouzici pro praci se selectem
* @example doc_example/Form.phps
*/
class Select extends HTMLTag {
	
	/**
	 * Konstruktor.
	 * @param String name
	 * @param Boolean multiple
	 * @param Integer size
	 * @param Boolean disabled
	 * @return void
	 */	 
	public function __construct($name = NULL, $multiple = NULL, $size = NULL,$disabled = NULL) {
		parent::__construct($value);
		$this->setTag("select");
		$this->setPair();
		if ($name){
			$this->addAtribut("name", $name);
		}
		if ($multiple){
			$this->addAtribut("multiple", $multiple);
		}
		if ($size){
			$this->addAtribut("size", $size);
		}
		if ($disabled){
			$this->addAtribut("disabled", $disabled);
		}
	}
	
	/**
	 * Přidá do selectu možnost (option)
	 * @param String text.
	 * @param Integer value.
	 * @param Boolean selected.
	 * @return void
	 */
	public function addOption($text = NULL,$value = NULL,$selected = NULL){
		$this->addValue(new Option($text,$value,$selected));
	}
}

class Option extends HTMLTag {
	
	/**
	 * Konstruktor.
	 * @param string text.
	 * @param string value.
	 * @param Boolean selected.
	 * @return void
	 */
	public function __construct($text = NULL, $value = NULL, $selected = NULL) {
		$this->setTag("option");
		$this->setPair();
		if ($text) {
			$this->addValue($text);
		}
		if ($value){
			$this->addAtribut("value",$value);
		}
		if ($selected){
			$this->addAtribut("selected", $selected);
		}
	}
}

class Label extends HTMLTag {
	
	/**
	 * Konstruktor
	 * @param String Hodnota labelu.
	 * @param string Nazev formularoveho prvku, ke kteremu label nalezi.
	 * @return void
	 */
	public function __construct($label,$nameOfFormElement = NULL) {
		$this->setTag("label");
		$this->setPair();
		$this->addValue($label);
		if ($nameOfFormElement) {
			$this->setID("eskymoLabel_".$nameOfFormElement);
		}
	}
}
