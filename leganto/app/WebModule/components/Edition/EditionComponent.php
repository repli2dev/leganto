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
class EditionComponent extends BaseComponent {

	public function __construct(IComponentContainer $parent = NULL, $name = NULL) {
		parent::__construct($parent, $name);
		if (!Environment::getUser()->isAllowed(Resource::EDITION, Action::INSERT)) {
			$this->unauthorized();
		}
	}

	public function renderEdit() {
		if (!Environment::getUser()->isAllowed(Resource::EDITION, Action::EDIT)) {
			$this->unauthorized();
		}
		$this->getTemplate()->setFile($this->getPath() . "editEdition.phtml");
		parent::render();
	}

	protected function createComponentAddEditionForm($name) {
		$form = $this->prepareForm();
		$form->addSubmit("submitted", "Add edition");
		$form->onSubmit[] = array($this, "addEditionFormSubmitted");
		return $form;
	}

	protected function createComponentEditEditionForm($name) {
		$edition = $this->getPresenter()->getParam("edition");
		$form = $this->prepareForm();
		$form->addSubmit("submitted", "Edit edition");
		$form->onSubmit[] = array($this, "editEditionFormSubmitted");

		// Fill with data
		$entity = Leganto::editions()->getSelector()->find($edition);
		$values["isbn10"] = $entity->isbn10;
		$values["isbn13"] = $entity->isbn13;
		$values["pages"] = $entity->pages;
		$values["published"] = $entity->published;
		$form->setDefaults($values);
		return $form;
	}

	protected function prepareForm($owner = "add") {
		$form = new BaseForm;
		$form->addText("isbn10", "ISBN 10", NULL, 10)
			->setOption("description", System::translate("Ten characters long ISBN."));
		$form->addText("isbn13", "ISBN 13", NULL, 13)
			->setOption("description", System::translate("Thirteen characters long ISBN."));
		$form->addText("pages", "Number of pages");
		$form->addText("published", "Year of publishing");
		$form->addFile("image", "Book cover")
			->addCondition(Form::FILLED)
			->addRule(Form::MIME_TYPE, "The file must be an image.", 'image/*')
			->addRule(Form::MAX_FILE_SIZE, "Image has to be smaller than 100 KB.", 1024 * 100);
		// Add cross rules
		$form["isbn10"]->addCondition(Form::FILLED)
			->addRule(Form::MAX_LENGTH, "The maximal length is 10 characters.", 10)
			->addRule(Form::MIN_LENGTH, "The minimal length is 10 characters.", 10);
		$form["isbn13"]->addCondition(Form::FILLED)
			->addRule(Form::MAX_LENGTH, "The maximal length is 13 characters.", 13)
			->addRule(Form::MIN_LENGTH, "The minimal length is 13 characters.", 13);
		$form["isbn10"]->addCondition(~Form::FILLED)
			->addConditionOn($form["isbn13"], ~Form::FILLED)
			->addRule(Form::FILLED, "Please fill at least one of ISBNs.");

		if ($owner == "edit") {
			$form["image"]->setOption("description", System::translate("(only if change)"));
		}
		return $form;
	}

	public function addEditionFormSubmitted(Form $form) {
		if (!Environment::getUser()->isAllowed(Resource::EDITION, Action::INSERT)) {
			$this->unauthorized();
		}
		$book = $this->getPresenter()->getParam("book");

		$values = $form->getValues();

		$entity = Leganto::editions()->createEmpty();
		try {
			$entity->idBookTitle = $book;
			$entity->isbn10 = $values["isbn10"];
			$entity->isbn13 = $values["isbn13"];
			$entity->pages = $values["pages"];
			$entity->published = $values["published"];
			$entity->inserted = new DateTime();
			$entity->persist();
			System::log("INSERT EDITION AND IMAGES TO BOOK '" . $book . "'");
			$tmpFile = $values["image"]->getTemporaryFile();
			if (!empty($tmpFile)) {
				$storage = new EditionImageStorage();
				$storage->store($entity, new File($tmpFile));
			} else { // Try to find image
				$imageFinder = new EditionImageFinder();
				$images = $imageFinder->get($entity);
				if (!empty($images)) {
					$storage = new EditionImageStorage();
					$storage->store($entity, new File(ExtraArray::firstValue($images)));
				}
			}
			$this->getPresenter()->flashMessage(System::translate("Thank you. Your edition has been successfully added and you are looking on it now."));
		} catch (Exception $e) {
			$this->unexpectedError($e);
			return;
		}
		$this->getPresenter()->redirect("Book:default", $book, $entity->getId());
	}

	public function editEditionFormSubmitted(Form $form) {
		if (!Environment::getUser()->isAllowed(Resource::EDITION, Action::EDIT)) {
			$this->unauthorized();
		}
		$book = $this->getPresenter()->getParam("book");
		$edition = $this->getPresenter()->getParam("edition");

		$values = $form->getValues();

		try {
			$entity = Leganto::editions()->getSelector()->find($edition);
			$entity->isbn10 = $values["isbn10"];
			$entity->isbn13 = $values["isbn13"];
			$entity->pages = $values["pages"];
			$entity->published = $values["published"];
			$entity->persist();
			System::log("EDIT EDITION '" . $edition . "' FROM BOOK '" . $book . "'");
			$tmpFile = $values["image"]->getTemporaryFile();
			if (!empty($tmpFile)) {
				$storage = new EditionImageStorage();
				$storage->store($entity, new File($tmpFile));
			}
			$this->getPresenter()->flashMessage(System::translate("Thank you. The edition has been successfully updated and you are looking on it now."));
		} catch (DuplicityException $e) {
			$this->getPresenter()->flashMessage(System::translate("Sorry, an edition with this ISBN already exists. Here is a form with original values."));
			$this->getPresenter()->redirect("this");
		} catch (Exception $e) {
			$this->unexpectedError($e);
			return;
		}
		$this->getPresenter()->redirect("Book:default", $book, $entity->getId());
	}

}