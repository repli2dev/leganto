<?php

/**
 * Inserting and editing book
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace FrontModule\Components;

use Leganto\DB\Factory,
    FrontModule\Forms\BaseForm,
    Nette\Forms\Form,
    Nette\Utils\Html,
    FrontModule\Components\BookList,
    Nette\DateTime,
    Exception,
    Leganto\External\EditionImageFinder,
    Leganto\Storage\EditionImageStorage,
    Leganto\External\GoogleBooksBookFinder,
    Leganto\ACL\Resource,
    Leganto\ACL\Action,
    Leganto\DB\Book\Selector,
    Nette\Environment,
    Leganto\IO\File;

class InsertingBook extends BaseComponent {

	private $book;

	/** @persistent */
	public $phase;
	private $state = array();

	public function __destruct() {
		$this->persistState();
	}

	public function bookFormSubmitted(Form $form) {
		$this->setValues((array) $form->getValues());
		$this->setPhase(3);
		// Insert a new author
		if (!$this->isInsertedBookRelated() && $form["newAuthor"]->isSubmittedBy()) {
			$this->handleNewAuthor();
		}
		// Save book
		elseif ($form["save"]->isSubmittedBy()) {
			$this->saveBook($form);
		}
		// Add or remove author
		else {
			// Add author
			if ($form["addAuthor"]->isSubmittedBy()) {
				$this->handleIncrementNumberOfAuthors();
			} else { // Remove author
				$this->handleDecrementNumberOfAuthors();
			}
			$this->resetBookForm();
		}
	}

	public function handleContinueToInsert($title) {
		$this->getPresenter()->flashMessage($this->translate("It can take a long time to insert a new book because the system tries to find editions of the book."));
		$this->setPhase(3);
		$this->setTitle($title);
	}

	public function handleDecrementNumberOfAuthors() {
		$number = $this->getNumberOfAuthors();
		if ($number > 1) {
			$this->setNumberOfAuthors($number - 1);
			$this->resetAuthor();
		}
	}

	public function handleIncrementNumberOfAuthors() {
		$number = $this->getNumberOfAuthors();
		$this->setNumberOfAuthors($number + 1);
	}

	public function handleNewAuthor() {
		if ($this->isEditing()) {
			$values = $this->getValues();
			$this->getPresenter()->redirect("Author:insert", NULL, $values["id_book_title"]);
		} else {
			$this->getPresenter()->redirect("Author:insert");
		}
	}

	public function render() {
		if (!$this->getUser()->isAllowed(Resource::BOOK, Action::INSERT)) {
			return;
		}
		switch ($this->getPhase()) {
			default:
			case 1:
				break;
			case 2:
				$this->getTemplate()->setFile($this->getPath() . "InsertingBookResult.latte");
				break;
			case 3:
				$this->getTemplate()->setFile($this->getPath() . "InsertingBookForm.latte");
				break;
		}
		return parent::render();
	}

	public function searchFormSubmitted(Form $form) {
		if (!$this->getUser()->isAllowed(Resource::BOOK, Action::INSERT)) {
			$this->unathorized();
		}
		$values = $form->getValues();

		$this->getComponent("bookList")->setSource(
			Factory::book()->getSelector()
				->searchByColumn(Selector::BOOK, $values["book_title"])
		);
		$this->setTitle($values["book_title"]);
		$this->setPhase(2);
	}

	public static function sendSignalWithAuthor(\Leganto\DB\Author\Entity $author) {
		$session = Environment::getService("session")->getSection("insertingBook");
		$session["signal"] = TRUE;
		$state = $session["state"];
		$state["author"] = $author->getId();
		$session["state"] = $state;
	}

	public function setBookToEdit(\Leganto\DB\Book\Entity $book) {
		$this->book = $book;
		$this->setPhase(3);
	}

	public function setRelatedBook(\Leganto\DB\Book\Entity $book) {
		$this->setBookNode($book->bookNode);
	}

	// PROTECTED METHODS

	protected function createComponentBookForm($name) {
		$form = new BaseForm($this, $name);

		// Basic information
		$form->addGroup("Basic information");
		$form->addText("book_title", "Book title")
			->addRule(Form::FILLED, "Fill the book title!");
		$form->addText("book_subtitle", "Book subtitle");

		// Authors
		if (!$this->isInsertedBookRelated()) {
			$form->addGroup("Authors");
			$authors = array(NULL => "---- " . $this->translate("Choose author") . " ----")
				+ Factory::author()->getSelector()->findAll()->orderBy("librarian_name")->fetchPairs("id_author", "librarian_name");
			$container = $form->addContainer("authors");
			// The user has just inserted a new author
			if ($this->getAuthor() != NULL) {
				$this->cleanAuthors();
				$this->handleIncrementNumberOfAuthors();
			}
			// This book is edited by user
			if ($this->isEditing() && $this->getEditedBook() != NULL) {
				$bookAuthors = Factory::author()->fetchAndCreateAll(Factory::author()->getSelector()->findAllByBook($this->getEditedBook()));
				if ($this->getAuthor() == NULL) {
					$this->setNumberOfAuthors(count($bookAuthors));
				}
			}
			for ($i = 0; $i < $this->getNumberOfAuthors(); $i++) {
				$last = $container->addSelect($i, "Author", $authors)
					->skipFirst()
					->addRule(Form::FILLED, "Choose the author of the book.");
			}
			if (isset($last)) {
				// Add text saying what to do
				$el = Html::el("span")->setClass("underForm");
				$el->setText($this->translate("If the author is not listed in the list above, please click on the button 'Create new author'."));
				$last->setOption("description", $el);
			}
			$form->addSubmit("addAuthor", "Add existing author")
				->getControlPrototype()->setId("addAuthor");
			if ($this->getNumberOfAuthors() > 1) {
				$form->addSubmit("removeAuthor", "Remove bottom author")
					->setValidationScope(FALSE)
					->getControlPrototype()->setId("removeAuthor");
				$form["removeAuthor"]->getControlPrototype()->setId("removeAuthor");
			}
			$form->addSubmit("newAuthor", "Create new author")
				->setValidationScope(FALSE)
				->getControlPrototype()->setId("newAuthor")
				->setHtmlId("newAuthor");
		}

		// Language
		$form->addGroup("Other");
		$languages = Factory::language()->getSelector()->findAll()->fetchPairs("id_language", "name");
		$form->addSelect("language", "Language", $languages)
			->setOption("description", $this->translate("(the language of the book title and subtitle)"));

		// Book node
		$form->addHidden("id_book");

		// Edited book
		$form->addHidden("id_book_title");

		// Submit button
		$form->addGroup();
		$form->addSubmit("save", "Save book");
		$form->onSuccess[] = array($this, "bookFormSubmitted");


		// Defaults
		if ($this->getValues() == NULL) {
			// The book which is edited
			if ($this->isEditing() && $this->getEditedBook() != NULL) {
				$defaults = array(
				    "id_book" => $this->getEditedBook()->bookNode,
				    "id_book_title" => $this->getEditedBook()->getId(),
				    "book_title" => $this->getEditedBook()->title,
				    "book_subtitle" => $this->getEditedBook()->subtitle,
				    "language" => $this->getEditedBook()->languageId
				);
				for ($i = 0; $i < count($bookAuthors); $i++) {
					$defaults["authors"][$i] = $bookAuthors[$i]->getId();
				}
			} else {
				$defaults = array();
				$defaults["language"] = $this->getContext()->getService("environment")->domain()->idLanguage;
				// Firstly user searches the book title and it whould be
				// filled when he wants to insert a new book
				if ($this->getTitle() != NULL) {
					$defaults["book_title"] = $this->getTitle();
				}
				// The inserted book is related to another
				if ($this->getBookNode() != NULL) {
					$defaults["id_book"] = $this->getBookNode();
				}
			}
		} else {
			$defaults = $this->getValues();
		}
		// -1 because of indexing from 0
		if ($this->getAuthor() != NULL) {
			$defaults["authors"] += array($this->getNumberOfAuthors() - 1 => $this->getAuthor());
		}
		$form->setDefaults($defaults);

		//$$this->resetState();

		return $form;
	}

	protected function createComponentSearchForm($name) {
		$form = new BaseForm($this, $name);

		$form->addText("book_title", "Book title")
			->addRule(Form::FILLED, "Fill the book title!")
			->addRule(Form::MIN_LENGTH, "The book title has to be at least 2 characters long.", 2);

		$form->addSubmit("submit_search", "Search");
		$form->onSubmit[] = array($this, "searchFormSubmitted");

		return $form;
	}

	protected function createComponentBookList($name) {
		return new BookList($this, $name);
	}

	protected function startUp() {
		$this->loadPersistedState();
	}

	// PRIVATE METHODS

	private function cleanAuthors() {
		$values = $this->getValues();
		if (empty($values)) {
			return;
		}
		// Clean authors (delete empty select list)
		$authors = array();
		foreach ($values["authors"] as $row) {
			if (!empty($row)) {
				$authors[] = $row;
			}
		}
		$this->setNumberOfAuthors(count($authors));
		$values["authors"] = $authors;
		$this->setValues($values);
	}

	private function getAuthor() {
		return isset($this->state["author"]) ? $this->state["author"] : NULL;
	}

	private function getBookNode() {
		return isset($this->state["bookNode"]) ? $this->state["bookNode"] : NULL;
	}

	private function getEditedBook() {
		return $this->book;
	}

	private function getNumberOfAuthors() {
		if (!isset($this->state["numberOfAuthors"])) {
			$this->state["numberOfAuthors"] = 1;
		}
		return $this->state["numberOfAuthors"];
	}

	private function getPhase() {
		if (empty($this->phase)) {
			$this->phase = 1;
		}
		if (empty($this->state["phase"])) {
			$this->state["phase"] = $this->phase;
		}
		return $this->phase;
	}

	private function getTitle() {
		return isset($this->state["title"]) ? $this->state["title"] : NULL;
	}

	private function getValues() {
		return isset($this->state["values"]) ? $this->state["values"] : NULL;
	}

	public function isEditing() {
		$values = $this->getValues();
		return isset($this->book) || !empty($values["id_book_title"]);
	}

	private function isInsertedBookRelated() {
		return ($this->getBookNode() != NULL);
	}

	private function loadPersistedState() {
		$session = $this->getContext()->getService("session")->getSection("insertingBook");
		if (!empty($session["signal"]) || $this->getPhase() != 1) {
			unset($session["signal"]);
			$this->state = $session["state"];
			$this->setPhase($session["state"]["phase"]);
		} else {
			$this->state["numberOfAuthors"] = $session["state"]["numberOfAuthors"];
		}
	}

	/**
	 * Persisting of the state is needed because in phase 3 the user
	 * can leave the page with component to insert a new author and come back
	 * to continue in inserting the book.
	 */
	private function persistState() {
		$session = $this->getContext()->getService("session")->getSection("insertingBook");
		$session->setExpiration(1800); // 30 minutes
		$session["state"] = $this->state;
	}

	private function resetAuthor() {
		if (isset($this->state["author"])) {
			unset($this->state["author"]);
		}
	}

	private function resetBookForm() {
		$form = $this->getComponent("bookForm");
		$this->removeComponent($form);
		$this->getComponent("bookForm");
	}

	private function resetNumberOfAuthors() {
		if (isset($this->state["numberOfAuthors"])) {
			unset($this->state["numberOfAuthors"]);
		}
	}

	private function saveBook(Form $form) {
		$values = $form->getValues();

		// Insert a new book
		if (empty($values["id_book_title"])) {
			if (!$this->getUser()->isAllowed(Resource::BOOK, Action::INSERT)) {
				$this->unauthorized();
			}
			$book = Factory::book()->createEmpty();
			$book->inserted = new DateTime;
			$flashMessage = $this->translate("The book has been successfuly inserted. If you have read the book, please add also your opinion.");
		}
		// Edit already inserted book
		else {
			if (!$this->getUser()->isAllowed(Resource::BOOK, Action::EDIT)) {
				$this->unauthorized();
			}
			$book = Factory::book()->getSelector()->find($values["id_book_title"]);
			$flashMessage = $this->translate("The book has been successfuly updated.");
		}
		// Persist the book
		$logger = $this->getContext()->getService("logger");
		try {
			$book->languageId = $values["language"];
			$book->title = $values["book_title"];
			$book->subtitle = $values["book_subtitle"];
			if ($this->isInsertedBookRelated()) {
				$book->bookNode = $values["id_book"];
			}
			$book->persist();
			if (empty($values["id_book_title"])) {
				$logger->log("INSERT BOOK '" . $book->getId() . "'");
			} else {
				$logger->log("UPDATE BOOK '" . $book->getId() . "'");
			}
		} catch (Exception $e) {
			$this->unexpectedError($e);
			return;
		}

		// If the book is not relation to another one, insert authors
		if (!$this->isInsertedBookRelated()) {
			$authors = Factory::author()->fetchAndCreateAll(
				Factory::author()->getSelector()->findAll()
					->where("id_author IN %l", $values["authors"])
			);
			try {
				Factory::book()->getUpdater()->setWrittenBy($book, $authors);
				$logger->log("INSERT AUTHORS TO BOOK '" . $book->getId() . "'");
			} catch (Exception $e) {
				$this->unexpectedError($e);
				return;
			}
		}

		// Find editions and images
		try {
			$language = Factory::language()->getSelector()->find($book->languageId);
			$imageFinder = new EditionImageFinder();
			$storage = new EditionImageStorage();
			$googleFinder = new GoogleBooksBookFinder($language->google);
			$info = $googleFinder->get($book);
			// Get editions
			if (!empty($info)) {
				$editions = Factory::edition()->getInserter()->insertByGoogleBooksInfo($book, $info);
			}
			// Try to find image foreach edition
			foreach ($editions AS $edition) {
				// Find images
				$images = $imageFinder->get($edition);
				// Store first one
				if (!empty($images)) {
					$storage->store($edition, new File(ExtraArray::firstValue($images)));
				}
			}
			$logger->log("LOOKUP FOR EDITIONS AND IMAGES FOR BOOK '" . $book->getId() . "'");
		} catch (Exception $e) {
			// Silent error
			//$this->unexpectedError($e, FALSE);
			$logger->log("LOOKUP FOR EDITIONS AND IMAGES FOR BOOK '" . $book->getId() . "' FAILED");
		}
		$this->setNumberOfAuthors(1);
		$this->getPresenter()->flashMessage($flashMessage, "success");
		$this->getPresenter()->flashMessage($this->translate("The system tried to load editions of the book") . " " . $book->title . $this->translate(", but the process is not reliable and you can insert editions manually."));
		$this->getPresenter()->redirect("Book:default", $book->getId());
	}

	private function setBookNode($bookNode) {
		$this->state["bookNode"] = $bookNode;
	}

	private function setNumberOfAuthors($number) {
		$this->state["numberOfAuthors"] = $number;
	}

	private function setPhase($phase) {
		$this->phase = $phase;
		$this->state["phase"] = $phase;
	}

	private function setTitle($title) {
		$this->getTemplate()->title = $title;
		$this->state["title"] = $title;
	}

	private function setValues(array $values) {
		$this->state["values"] = $values;
	}

}