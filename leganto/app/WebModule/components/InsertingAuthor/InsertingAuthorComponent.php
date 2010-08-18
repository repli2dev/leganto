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

class InsertingAuthorComponent extends BaseComponent
{

	/** @persistent */
	public $backlink;

	public function render() {
		if (!Environment::getUser()->isAllowed(Resource::AUTHOR, Action::INSERT)) {
		    $this->unathorized();
		}
		parent::render();
	}

	public function formSubmitted(Form $form) {
		if (!Environment::getUser()->isAllowed(Resource::AUTHOR, Action::INSERT)) {
		    $this->unathorized();
		}
		// Load data from form
		$values = $form->getValues();
		// Prepare entity and persist it
		$author = Leganto::authors()->createEmpty();
		$author->type = $values["type"];
		if($values["type"] == AuthorEntity::PERSON) {
			$author->firstname = $values["first_name"];
			$author->lastname = $values["last_name"];
		} else {
			$author->groupname = $values["group_name"];
		}
		$author->inserted = new DateTime;
		try {
		    $author->persist();
<<<<<<< HEAD
=======
		    System::log("INSERT AUTHOR '".$author->getId()."'");
>>>>>>> 553097ca9eee0f013cbb3d268ce8ac3ba1d5830b
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

	public function setBacklink($backlink) {
	    $this->backlink = $backlink;
	}

	// PROTECTED METHODS

	protected function createComponentForm($name) {
	    $form = new BaseForm($this, $name);
	    $types = array(
		    AuthorEntity::PERSON    =>	System::translate("Person"),
		    AuthorEntity::GROUP	    =>	System::translate("Group")
	    );
	    $form->addGroup()->setOption('container', Html::el('div'));
	    $form->addSelect("type","Type of author",$types)
		    ->addRule(Form::FILLED,"Please choose type of author.")
		    ->addCondition(Form::EQUAL, AuthorEntity::PERSON)
			->toggle("person")
		    ->endCondition()
		    ->addCondition(Form::EQUAL, AuthorEntity::GROUP)
			->toggle("group");
	    
	    $form->addGroup()->setOption('container', Html::el('div')->id('person'));
	    $form->addText("first_name","First name");
	    $form->addText("last_name","Last name")
		    ->addConditionOn($form["type"], Form::EQUAL, AuthorEntity::PERSON)
		    ->addRule(Form::FILLED,"Please fill at least the last name.");

	    $form->addGroup()->setOption('container', Html::el('div')->id('group'));
	    $form->addText("group_name","Group name")
		    ->addConditionOn($form["type"], Form::EQUAL, AuthorEntity::GROUP)
		    ->addRule(Form::FILLED,"Please fill the group name.");

	    $form->addGroup()->setOption('container', Html::el('div'));
	    $form->addSubmit("insert", "Insert");
	    $form->onSubmit[] = array($this, "formSubmitted");

	    return $form;
	}

//	protected function createComponentInsertForm($name) {
//		$form = new BaseForm($this, $name);
//		$types = array(
//			AuthorEntity::PERSON	=>	System::translate("Person"),
//			AuthorEntity::GROUP	=>	System::translate("Group")
//		);
//		if($this->type == AuthorEntity::PERSON) {
//			$form->addText("first_name","First name");
//			$form->addText("last_name","Last name")
//				->addRule(Form::FILLED,"Please fill at least the last name.");
//		} else {
//			$form->addText("group_name","Group name")
//				->addRule(Form::FILLED,"Please fill the group name.");
//		}
//		$form->addSubmit("addAuthor","Add author");
//		$form->addSubmit("changeType","Change type")
//			->setValidationScope(FALSE);
//		$form->onSubmit[] = array($this,"insertFormSubmitted");
//
//		return $form;
//	}
//
//	protected function createComponentChangeTypeForm($name) {
//		$form = new BaseForm($this, $name);
//		$types = array(
//			AuthorEntity::PERSON	=>	System::translate("Person"),
//			AuthorEntity::GROUP	=>	System::translate("Group")
//		);
//		$form->addSelect("type","Type of author",$types)
//			->addRule(Form::FILLED,"Please choose type of author.");
//		$form->addSubmit("changeType","Choose type");
//		$form->onSubmit[] = array($this,"changeTypeFormSubmitted");
//
//		return $form;
//	}
}