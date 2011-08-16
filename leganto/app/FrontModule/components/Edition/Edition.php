<?php

/**
 * Edition component
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace FrontModule\Components;

use Nette\ComponentModel\IContainer,
    Nette\Environment,
    Leganto\DB\Factory,
    Exception,
    Leganto\ORM\Exceptions\DuplicityException,
    Leganto\ACL\Resource,
    Leganto\ACL\Action,
    FrontModule\Forms\BaseForm,
    Nette\Forms\Form,
    Nette\DateTime,
    Leganto\External\EditionImageFinder,
    Leganto\Storage\EditionImageStorage,
    Leganto\IO\File,
    Leganto\Utils\ExtraArray,
    DibiDriverException;

class Edition extends BaseComponent {

	public function __construct(IContainer $parent = NULL, $name = NULL) {
		parent::__construct($parent, $name);
		if (!$this->getUser()->isAllowed(Resource::EDITION, Action::INSERT)) {
			$this->unauthorized();
		}
	}

	public function renderEdit() {
		if (!$this->getUser()->isAllowed(Resource::EDITION, Action::EDIT)) {
			$this->unauthorized();
		}
		$this->getTemplate()->setFile($this->getPath() . "EditEdition.latte");
		parent::render();
	}

	protected function createComponentAddEditionForm($name) {
		$form = $this->prepareForm();
		$form->addSubmit("submitted", "Add edition");
		$form->onSuccess[] = array($this, "addEditionFormSubmitted");
		return $form;
	}

	protected function createComponentEditEditionForm($name) {
		$edition = $this->getPresenter()->getParam("edition");
		$form = $this->prepareForm();
		$form->addSubmit("submitted", "Edit edition");
		$form->onSuccess[] = array($this, "editEditionFormSubmitted");

		// Fill with data
		$entity = Factory::edition()->getSelector()->find($edition);
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
			->setOption("description", $this->translate("Ten characters long ISBN."));
		$form->addText("isbn13", "ISBN 13", NULL, 13)
			->setOption("description", $this->translate("Thirteen characters long ISBN."));
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
			$form["image"]->setOption("description", $this->translate("(only if change)"));
		}
		return $form;
	}

	public function addEditionFormSubmitted(Form $form) {
		if (!$this->getUser()->isAllowed(Resource::EDITION, Action::INSERT)) {
			$this->unauthorized();
		}
		$book = $this->getPresenter()->getParam("book");

		$values = $form->getValues();

		$entity = Factory::edition()->createEmpty();
		$logger = $this->getContext()->getService("logger");
		try {
			$entity->idBookTitle = $book;
			$entity->isbn10 = $values["isbn10"];
			$entity->isbn13 = $values["isbn13"];
			$entity->pages = $values["pages"];
			$entity->published = $values["published"];
			$entity->inserted = new DateTime();
			$entity->persist();
			$logger->log("INSERT EDITION AND IMAGES TO BOOK '" . $book . "'");
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
			$this->getPresenter()->flashMessage($this->translate("Thank you. Your edition has been successfully added and you are looking on it now."));
		} catch (DuplicityException $e) {
			$this->getPresenter()->flashMessage($this->translate("Sorry, an edition with this ISBN already exists. Here is a form with original values."));
			$this->getPresenter()->redirect("this");
		} catch (Exception $e) {
			$this->unexpectedError($e);
			return;
		}
		$this->getPresenter()->redirect("Book:default", $book, $entity->getId());
	}

	public function editEditionFormSubmitted(Form $form) {
		if (!$this->getUser()->isAllowed(Resource::EDITION, Action::EDIT)) {
			$this->unauthorized();
		}
		$book = $this->getPresenter()->getParam("book");
		$edition = $this->getPresenter()->getParam("edition");

		$values = $form->getValues();
		$logger = $this->getContext()->getService("logger");
		try {
			$entity = Factory::edition()->getSelector()->find($edition);
			$entity->isbn10 = $values["isbn10"];
			$entity->isbn13 = $values["isbn13"];
			$entity->pages = $values["pages"];
			$entity->published = $values["published"];
			$entity->persist();
			$logger->log("EDIT EDITION '" . $edition . "' FROM BOOK '" . $book . "'");
			$tmpFile = $values["image"]->getTemporaryFile();
			if (!empty($tmpFile)) {
				$storage = new EditionImageStorage();
				$storage->store($entity, new File($tmpFile));
			}
			$this->getPresenter()->flashMessage($this->translate("Thank you. The edition has been successfully updated and you are looking on it now."));
		} catch (DuplicityException $e) {
			$form->addError("Sorry, an edition with this ISBN already exists. Here is a form with original values.");
			return;
		} catch (Exception $e) {
			$this->unexpectedError($e);
			return;
		}
		$this->getPresenter()->redirect("Book:default", $book, $entity->getId());
	}

}