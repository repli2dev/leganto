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

class InsertingBookComponent extends BaseComponent {


	/**
	 * @persistent
	 */
	public $phase;

	/**
	 * @persistent
	 */
	public $numOfAuthors = 1;

	public function __construct(/*Nette\*/IComponentContainer $parent = NULL, $name = NULL){
		parent::__construct($parent,$name);
	}

	public function render() {
		switch($this->phase) {
			default:
			case 1:
				break;
			case 2:
				$this->getTemplate()->setFile($this->getPath()."insertingBookResult.phtml");
				break;
			case 3:
				$this->getTemplate()->setFile($this->getPath()."insertingBookForm.phtml");
				break;
		}
		parent::render();
	}

	public function handleContinueToInsert() {
		$this->phase = 3;
	}

	public function searchFormSubmitted(Form $form) {
		$values = $form->getValues();

		$this->getComponent("bookList")->setSource(
			Leganto::books()->getSelector()
				->searchByColumn(BookSelector::BOOK, $values["book_title"])
		);

		$this->phase = 2;
	}

	public function insertFormSubmitted(Form $form) {
		$values = $form->getValues();
		if($form["insert"]->isSubmittedBy()){
			// Insert book
			$book= Leganto::books()->createEmpty();
			$book->languageId = $values["language"];
			$book->title = $values["book_title"];
			$book->subtitle = $values["book_subtitle"];
			$book->inserted = new DateTime;

			$book->persist();
			$book->getId();

			// Insert authors
			$authors = Leganto::authors()->fetchAndCreateAll(
				Leganto::authors()->getSelector()->findAll()
					->where("id_author IN %l", $values["authors"])
			);
			Leganto::books()->getUpdater()->setWrittenBy($book, $authors);

		} else  {
			if($form["removeAuthor"]->isSubmittedBy()){
				if($this->numOfAuthors > 1){
					$this->numOfAuthors--;
				}
			} else {
				$this->numOfAuthors++;
			}
			// FIXME: vymyslet neco slusneho
			$values = $form->getValues();
			$this->removeComponent($form);
			$form = $this->getComponent("insertForm");
			$form->setDefaults($values);
		}
	}

	// PROTECTED METHODS

	protected function createComponentInsertForm($name) {
		$form = new BaseForm($this, $name);
		
		$form->addGroup("Basic information");
		$form->addText("book_title", "Book title")
			->addRule(Form::FILLED, "Fill the book title!");
		$form->addText("book_subtitle", "Book subtitle");

		// Authors
		$form->addGroup("Authors");
		$authors = array(NULL => "---- " . System::translate("Choose author") . " ----") + Leganto::authors()->getSelector()->findAll()->orderBy("full_name")->fetchPairs("id_author", "full_name");
		$container = $form->addContainer("authors");
		for($i=0; $i < $this->numOfAuthors; $i++) {
			$container->addSelect($i, "Author", $authors)
				->skipFirst()
				->addRule(Form::FILLED,"Choose the author of the book.");
		}
		$form->addSubmit("addAuthor","Add")
			->getControlPrototype()->setId("addAuthor");
		// TODO: skryt pri poctu autoru = 1
		$form->addSubmit("removeAuthor","Remove")
			->setValidationScope(FALSE);
			
		$form["removeAuthor"]->getControlPrototype()->setId("removeAuthor");

		// Language
		$form->addGroup("Other");
		$languages = Leganto::languages()->getSelector()->findAll()->fetchPairs("id_language", "name");
		$form->addSelect("language", "Language", $languages);

		// Submit button
		$form->addGroup();
		$form->addSubmit("insert","Insert book");
		$form->onSubmit[] = array($this,"insertFormSubmitted");

		// Defaults
		$form->setDefaults(array("language" => System::domain()->idLanguage));
		return $form;
	}

	protected function createComponentSearchForm($name) {
		$form = new BaseForm($this, $name);

		$form->addText("book_title", "Book title")
			->addRule(Form::FILLED, "Fill the book title!")
			->addRule(Form::MIN_LENGTH, "The book title has to be at least 3 characters long.", 3);

		$form->addSubmit("submit_search", "Search");
		$form->onSubmit[] = array($this, "searchFormSubmitted");

		return $form;
	}

	protected function createComponentBookList($name) {
		return new BookListComponent($this, $name);
	}
}

