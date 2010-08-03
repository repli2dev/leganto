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

class EditionComponent extends BaseComponent {

	public function  __construct(IComponentContainer $parent = NULL, $name = NULL) {
		parent::__construct($parent, $name);
	}

	public function renderEdit() {
		$this->getTemplate()->setFile($this->getPath()."editEdition.phtml");
		parent::render();
	}

	protected function createComponentAddEditionForm($name) {
		$form = $this->prepareForm();
		$form->addSubmit("submitted","Add edition");
		$form->onSubmit[] = array($this,"addEditionFormSubmitted");
		return $form;
		
	}

	protected function createComponentEditEditionForm($name) {
		$edition = $this->getPresenter()->getParam("edition");
		$form = $this->prepareForm();
		$form->addSubmit("submitted","Edit edition");
		$form->onSubmit[] = array($this,"editEditionFormSubmitted");

		// Fill with data
		$entity = Leganto::editions()->getSelector()->find($edition);
		$values["isbn10"] = $entity->isbn10;
		$values["isbn13"] = $entity->isbn13;
		$values["pages"] = $entity->pages;
		$values["published"] = $entity->published;
		$form->setDefaults($values);
		return $form;
	}

	protected function prepareForm() {
		$form = new BaseForm;
		$form->addText("isbn10", "ISBN 10")
			->addRule(Form::MAX_LENGTH,"Maximal length can be 10 characters.",10)
			->addRule(Form::MIN_LENGTH,"Minimal length can be 10 characters.",10)
			->setOption("description",System::translate("Nine characters long ISBN."));
		$form->addText("isbn13", "ISBN 13")
			->addRule(Form::MAX_LENGTH,"Maximal length can be 13 characters.",13)
			->addRule(Form::MIN_LENGTH,"Minimal length can be 13 characters.",13)
			->setOption("description",System::translate("Thirdteen characters long ISBN."));
		$form->addText("pages","Number of pages");
		$form->addText("published","Year of publishing");
		return $form;
	}

	public function addEditionFormSubmitted(Form $form) {
		$book = $this->getPresenter()->getParam("book");
		
		$values = $form->getValues();

		$entity = Leganto::editions()->createEmpty();
		$entity->idBookTitle = $book;
		$entity->isbn10 = $values["isbn10"];
		$entity->isbn13 = $values["isbn13"];
		$entity->pages = $values["pages"];
		$entity->published = $values["published"];
		$entity->inserted = new DateTime();
		$entity->persist();
		$this->getPresenter()->flashMessage(System::translate("Thank you. Your edition was successfully added and you are looking on it now."));
		$this->getPresenter()->redirect("Book:default",$book,$entity->getId());
	}

	public function editEditionFormSubmitted(Form $form) {
		$book = $this->getPresenter()->getParam("book");
		$edition = $this->getPresenter()->getParam("edition");
		
		$values = $form->getValues();

		$entity = Leganto::editions()->getSelector()->find($edition);
		$entity->isbn10 = $values["isbn10"];
		$entity->isbn13 = $values["isbn13"];
		$entity->pages = $values["pages"];
		$entity->published = $values["published"];
		$entity->persist();
		$this->getPresenter()->flashMessage(System::translate("Thank you. Edition was successfully updated and you are looking on it now."));
		$this->getPresenter()->redirect("Book:default",$book,$entity->getId());
		
	}


}