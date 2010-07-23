<?php
/**
 * The source file is subject to the license located on web
 * "http://code.google.com/p/preader/".
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (repli2dev@gmail.com)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 */

/**
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$Id$
 *
 */

class InsertingShelfComponent extends BaseComponent {

    private $shelf;

    public function setShelf(ShelfEntity $shelf) {
	$this->shelf = $shelf;
    }

    public function formSubmitted(Form $form) {
	$values = $form->getValues();
	Debug::dump($values["id_shelf"]);
	// Update old one
	if (!empty($values["id_shelf"])) {
	    $shelf = Leganto::shelves()->getSelector()->find($values["id_shelf"]);
	}
	// Insert new one
	else {
	    $shelf = Leganto::shelves()->createEmpty();
	    $shelf->inserted = new Datetime();
	    $shelf->user = System::user()->getId();
	}
	try {
	    $shelf->name = $values["name"];
	    $shelf->type = $values["type"];
	    $shelf->persist();
	    $this->getPresenter()->flashMessage(System::translate("The shelf has been successfuly saved."), "success");
	}
	catch(Exception $e) {
	    $this->getPresenter()->flashMessage(System::translate('Unexpected error happened.'), "error");
	    error_log($e->getTraceAsString());
	    return;
	}
	$this->getPresenter()->redirect("User:shelves", System::user()->getId());
    }

    protected function createComponentForm($name) {
	$form = new BaseForm($name);

	// Name
	$form->addText("name", "Name")
		->addRule(Form::FILLED, "Fill the name of the shelf.");
	// Type
	$type = array(
	    "general"	=> System::translate("General"),
	    "owned"	=> System::translate("Owned"),
	    "wanted"	=> System::translate("Wanted"),
	);
	$form->addSelect("type", "Type", $type);
	// ID?
	$form->addHidden("id_shelf");

	// Submit settings
	$form->addSubmit("shelfSubmit", "Save");
	$form->onSubmit[] = array($this, "formSubmitted");

	// Default values
	if (!empty($this->shelf)) {
	    $form->setDefaults(array(
		"id_shelf"  => $this->shelf->getId(),
		"name"	    => $this->shelf->name,
		"type"	    => $this->shelf->type
	    ));
	}

	return $form;
    }

}