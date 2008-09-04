<?php
class MyForm extends Form {

	public function __construct() {
		parent::__construct("nameOfForm","","post",TRUE); // Abych nemusel u teto tridy vyplnovat konstruktor, napisu novy. Nesmim v nem ale zapomenout zavolat konstruktor zdedeny. 
	}
	
	public function renderForm($name,$action,$method,$enctype) { // V teto metode naplnim formular, na zacatku ale zase musim zavolat zdedenou metodu.
		parent::renderForm($name,$action,$method,$enctype);
		$this->addFieldset("description");
		$this->addTextInput(TRUE,"nameOfTextInput_1","Text:");
		$this->addTextInput(FALSE,"nameOfTextInput_2","Text2:");
		$this->addSubmitButton("mySubmitButton","Odeslat");
	}
	
	protected function isSend() { // Musim definovat tuto metodu, ktera urci, za jakych podminek je formular odeslan.
		if ($_POST["mySubmitButton"]) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
	protected function getDataToFill($id) { // Tato metoda naplni polozky formulare (pokud nebyl odeslan). V tomto priklade je volana s $id = TRUE, ale $id muze byt cokoliv (treba ID polozky v databazi).
		return array("nameOfTextInput_2" => "Hello world!");
	}
	
	protected function execute() { // V teto metode piseme, co se ma stat, pokud je formular uspesne odeslan.
		$this->setTag("p");
		$this->setPair();
		$this->addValue(new String("This is result of my form."));
	}
}
$page = new Page();
$form = new MyForm();
$page->addValue($form);
$page->view();
?>