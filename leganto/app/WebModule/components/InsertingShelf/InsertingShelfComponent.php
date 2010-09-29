<?php

/**
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
class InsertingShelfComponent extends BaseComponent {

	private $shelf;
	/** @persistent */
	public $backlink;

	public function formSubmitted(Form $form) {
		if (!Environment::getUser()->isAllowed(Resource::SHELF, Action::INSERT)) {
			$this->unauthorized();
		}
		$values = $form->getValues();
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
			System::log("INSERT SHELF '" . $shelf->getId() . "'");
			$this->getPresenter()->flashMessage(System::translate("The shelf has been successfuly saved."), "success");
		} catch (Exception $e) {
			$this->unexpectedError($e);
			return;
		}
		if (empty($this->backlink)) {
			$this->getPresenter()->redirect("User:shelves", System::user()->getId());
		} else {
			$this->getPresenter()->redirectUri($this->backlink);
		}
	}

	public function render() {
		if (!Environment::getUser()->isAllowed(Resource::SHELF, Action::INSERT)) {
			return;
		}
		return parent::render();
	}

	public function setBacklink($backlinkUri) {
		$this->backlink = $backlinkUri;
	}

	public function setShelf(ShelfEntity $shelf) {
		$this->shelf = $shelf;
	}

	protected function createComponentForm($name) {
		$form = new BaseForm($this, $name);

		// Name
		$form->addText("name", "Name")
			->addRule(Form::FILLED, "Fill the name of the shelf.");
		// Type
		$type = array(
		    "general" => System::translate("General"),
		    "owned" => System::translate("Owned"),
		    "wanted" => System::translate("Wanted"),
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
			    "id_shelf" => $this->shelf->getId(),
			    "name" => $this->shelf->name,
			    "type" => $this->shelf->type
			));
		}

		return $form;
	}

}