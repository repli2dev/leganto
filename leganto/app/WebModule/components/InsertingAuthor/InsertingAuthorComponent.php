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

	/** @persistent */
	public $backlink;

	public function render() {
		if (!Environment::getUser()->isAllowed(Resource::AUTHOR, Action::INSERT)) {
		    $this->unathorized();
		}
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
		if (!Environment::getUser()->isAllowed(Resource::AUTHOR, Action::INSERT)) {
		    $this->unathorized();
		}
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
		try {
		    $author->persist();
		    // TODO: co delat tedka? Vratit uzivatele na zacatek (s tim ze muze vlozit dalsiho), nebo na stranku autora?
		    $this->getPresenter()->flashMessage(System::translate("New author has been successfuly inserted."),'success');
		}
		catch(Exception $e) {
		    $this->unexpectedError($e);
		    return; 
		}
		if (empty($this->backlink)) {
		    $this->getPresenter()->redirect("Author:insert");
		}
		else {
		    if ($this->backlink == "Book:insert") {
			InsertingBookComponent::sendSignalWithAuthor($author);
		    }
		    $this->getPresenter()->redirect($this->backlink);
		}
	}

	public function changeTypeFormSubmitted(Form $form) {
		$this->type = $form["type"]->getValue();
	}



	public function setBacklink($backlink) {
	    $this->backlink = $backlink;
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