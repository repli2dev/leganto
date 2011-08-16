<?php

/**
 * Inserting shelf
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace FrontModule\Components;

use Leganto\DB\Factory,
    Exception,
    FrontModule\Forms\BaseForm,
    Nette\Forms\Form,
    Leganto\ACL\Resource,
    Leganto\ACL\Action,
    Nette\DateTime;

class InsertingShelf extends BaseComponent {

	private $shelf;

	/** @persistent */
	public $backlink;

	public function formSubmitted(Form $form) {
		if (!$this->getUser()->isAllowed(Resource::SHELF, Action::INSERT)) {
			$this->unauthorized();
		}
		$values = $form->getValues();
		// Update old one
		if (!empty($values["id_shelf"])) {
			$shelf = Factory::shelf()->getSelector()->find($values["id_shelf"]);
		} else { // Insert new one
			$shelf = Factory::shelf()->createEmpty();
			$shelf->inserted = new DateTime();
			$shelf->user = $this->getUser()->getId();
		}
		$logger = $this->getContext()->getService("logger");
		try {
			$shelf->name = $values["name"];
			$shelf->type = $values["type"];
			$shelf->persist();
			$logger->log("INSERT SHELF '" . $shelf->getId() . "'");
			$this->getPresenter()->flashMessage($this->translate("The shelf has been successfuly saved."), "success");
		} catch (Exception $e) {
			$this->unexpectedError($e);
			return;
		}
		if (empty($this->backlink)) {
			$this->getPresenter()->redirect("User:shelves", $this->getUser()->getId());
		} else {
			$this->getPresenter()->redirectUri($this->backlink);
		}
	}

	public function render() {
		if (!$this->getUser()->isAllowed(Resource::SHELF, Action::INSERT)) {
			return;
		}
		return parent::render();
	}

	public function setBacklink($backlinkUri) {
		$this->backlink = $backlinkUri;
	}

	public function setShelf(\Leganto\DB\Shelf\Entity $shelf) {
		$this->shelf = $shelf;
	}

	protected function createComponentForm($name) {
		$form = new BaseForm($this, $name);

		// Name
		$form->addText("name", "Name")
			->addRule(Form::FILLED, "Fill the name of the shelf.");
		// Type
		$type = array(
		    "general" => $this->translate("General"),
		    "owned" => $this->translate("Owned"),
		    "wanted" => $this->translate("Wanted"),
		    "reading" => $this->translate("Reading just now"),
		);
		$form->addSelect("type", "Type", $type);
		// ID?
		$form->addHidden("id_shelf");

		// Submit settings
		$form->addSubmit("shelfSubmit", "Save");
		$form->onSuccess[] = array($this, "formSubmitted");

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