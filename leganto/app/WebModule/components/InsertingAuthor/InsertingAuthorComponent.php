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
class InsertingAuthorComponent extends BaseComponent {

	/** @var AuthorEntity */
	private $author;
	/** @persistent */
	public $backlink;
	/** @persistent */
	public $backlinkArgs;

	public function render() {
		if (!Environment::getUser()->isAllowed(Resource::AUTHOR, Action::INSERT)) {
			$this->unathorized();
		}
		parent::render();
	}

	public function formSubmitted(Form $form) {
		// Load data from form
		$values = $form->getValues();
		if (empty($values["id_author"]) && !Environment::getUser()->isAllowed(Resource::AUTHOR, Action::INSERT)) {
			$this->unathorized();
		}
		if (!empty($values["id_author"]) && !Environment::getUser()->isAllowed(Resource::AUTHOR, Action::EDIT)) {
			$this->unauthorized();
		}
		// Prepare entity and persist it
		if (empty($values["id_author"])) {
			$author = Leganto::authors()->createEmpty();
			$flashMessage = System::translate("New author has been successfuly inserted.");
		} else {
			$author = Leganto::authors()->getSelector()->find($values["id_author"]);
			$flashMessage = System::translate("Author has been successfuly updated.");
		}

		$author->type = $values["type"];
		if ($values["type"] == AuthorEntity::PERSON) {
			$author->firstname = $values["first_name"];
			$author->lastname = $values["last_name"];
		} else {
			$author->groupname = $values["group_name"];
		}
		$author->inserted = new DateTime;
		try {
			$author->persist();
			// Filling of log messages has to be after persisting
			if (empty($values["id_author"])) {
				$logMessage = "INSERT AUTHOR '" . $author->getId() . "'";
			} else {
				$logMessage = "UPDATE AUTHOR '" . $author->getId() . "'";
			}
			System::log($logMessage);
			$this->getPresenter()->flashMessage($flashMessage, 'success');
		} catch (Exception $e) {
			$this->unexpectedError($e);
			return;
		}
		if (empty($this->backlink)) {
			$this->getPresenter()->redirect("Author:insert");
		} else {
			if ($this->backlink == "Book:insert" || $this->backlink == "Book:edit") {
				InsertingBookComponent::sendSignalWithAuthor($author);
			}
			$this->getPresenter()->redirect($this->backlink, $this->backlinkArgs);
		}
	}

	public function setAuthor(AuthorEntity $author) {
		$this->author = $author;
	}

	public function setBacklink($backlink, $args = NULL) {
		$this->backlink = $backlink;
		$this->backlinkArgs = $args;
	}

	// PROTECTED METHODS

	protected function createComponentForm($name) {
		$form = new BaseForm($this, $name);
		$types = array(
		    AuthorEntity::PERSON => System::translate("Person"),
		    AuthorEntity::GROUP => System::translate("Group")
		);
		$form->addGroup()->setOption('container', Html::el('div'));
		$form->addSelect("type", "Type of author", $types)
			->addRule(Form::FILLED, "Please choose the type of the author.")
			->addCondition(Form::EQUAL, AuthorEntity::PERSON)
			->toggle("person")
			->endCondition()
			->addCondition(Form::EQUAL, AuthorEntity::GROUP)
			->toggle("group");

		$form->addGroup()->setOption('container', Html::el('div')->id('person'));
		$form->addText("first_name", "First name");
		$form->addText("last_name", "Last name")
			->addConditionOn($form["type"], Form::EQUAL, AuthorEntity::PERSON)
			->addRule(Form::FILLED, "Please fill at least the last name.");

		$form->addGroup()->setOption('container', Html::el('div')->id('group'));
		$form->addText("group_name", "Group name")
			->addConditionOn($form["type"], Form::EQUAL, AuthorEntity::GROUP)
			->addRule(Form::FILLED, "Please fill the group name.");

		$form->addGroup()->setOption('container', Html::el('div'));
		$form->addSubmit("insert", "Save");
		$form->onSubmit[] = array($this, "formSubmitted");

		$form->addHidden("id_author");

		if (isset($this->author)) {
			$form->setDefaults(array(
			    "type" => $this->author->type,
			    "first_name" => $this->author->firstname,
			    "group_name" => $this->author->groupname,
			    "id_author" => $this->author->getId(),
			    "last_name" => $this->author->lastname,
			));
		}
		return $form;
	}

}
