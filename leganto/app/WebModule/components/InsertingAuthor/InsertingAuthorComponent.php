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

class InsertingAuthorComponent extends BaseComponent {

	/** @persistent */
	public $type;

	public function render() {
		switch($this->type) {
			case AuthorEntity::GROUP:
			case AuthorEntity::PERSON:
				$this->getTemplate()->setFile($this->getPath()."insertingAuthorForm.phtml");
				break;
			default:
				break;
		}
		parent::render();
	}

	public function insertFormSubmitted(Form $form) {
		// Redirect if user ordered change of type of author
		if($form["changeType"]->isSubmittedBy()) {
			$this->getPresenter()->redirect("Author:insert");
			return;
		}
		// Load data from form
		$values = $form->getValues();
		// Prepare entity and persist it
		$author = Leganto::authors()->createEmpty();
		$author->type = $this->type;
		if($this->type == AuthorEntity::PERSON) {
			$author->firstname = $values["first_name"];
			$author->lastname = $values["last_name"];
		} else {
			$author->groupname = $values["group_name"];
		}
		$author->inserted = new DateTime;
		$author->persist();
		// TODO: co delat tedka? Vratit uzivatele na zacatek (s tim ze muze vlozit dalsiho), nebo na stranku autora?
		$session = Environment::getSession("insertingBook");
		if(!isSet($session["values"])) {
			$this->getPresenter()->flashMessage(System::translate("New author was successfully inserted. You can insert another now or you can continue in browsing our page."),'success');
			$this->getPresenter()->redirect("Author:insert");
		} else {
			$this->getPresenter()->flashMessage(System::translate("New author was successfully inserted. At the same time, the author was appended to your filled form."),'success');
			$session["authorId"] = $author->getId();
			$this->getPresenter()->redirect("Book:insert");
		}
	}

	public function changeTypeFormSubmitted(Form $form) {
		$this->type = $form["type"]->getValue();
	}

	// PROTECTED METHODS

	protected function createComponentInsertForm($name) {
		$form = new BaseForm($this, $name);
		$types = array(
			AuthorEntity::PERSON	=>	System::translate("Person"),
			AuthorEntity::GROUP	=>	System::translate("Group")
		);
		if($this->type == AuthorEntity::PERSON) {
			$form->addText("first_name","First name");
			$form->addText("last_name","Last name")
				->addRule(Form::FILLED,"Please fill at least the last name.");
		} else {
			$form->addText("group_name","Group name")
				->addRule(Form::FILLED,"Please fill the group name.");
		}
		$form->addSubmit("addAuthor","Add author");
		$form->addSubmit("changeType","Change type")
			->setValidationScope(FALSE);
		$form->onSubmit[] = array($this,"insertFormSubmitted");

		return $form;
	}

	protected function createComponentChangeTypeForm($name) {
		$form = new BaseForm($this, $name);
		$types = array(
			AuthorEntity::PERSON	=>	System::translate("Person"),
			AuthorEntity::GROUP	=>	System::translate("Group")
		);
		$form->addSelect("type","Type of author",$types)
			->addRule(Form::FILLED,"Please choose type of author.");
		$form->addSubmit("changeType","Choose type");
		$form->onSubmit[] = array($this,"changeTypeFormSubmitted");

		return $form;
	}
}