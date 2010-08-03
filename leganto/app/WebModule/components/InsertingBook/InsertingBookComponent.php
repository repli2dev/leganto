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
	public $bookNode;
    
	/**
	 * @persistent
	 */
	public $phase;

	// Going through session
	public $numOfAuthors = 1;

	private $title;

	public function __construct(/*Nette\*/IComponentContainer $parent = NULL, $name = NULL){
		parent::__construct($parent,$name);
		$session = Environment::getSession("insertingBook");
		if(isSet($session["values"])) {
			$this->phase = 3;
			$values = $session["values"]; // needed because 3 dimensional arrays do not work
			// Workaround about hiding authors when the inserted book is connected to another book
			if (!empty($values["id_book"])) {
			    $this->bookNode = $values["id_book"];
			}
			// Clean authors (delete empty select list)
			$authors = array();
			foreach ($values["authors"] as $row) {
				if(!empty($row)) {
					$authors[] = $row;
				}
			}
			$session["numOfAuthors"] = count($authors);
			$values["authors"] = $authors;
			// If authorId is set, it means that user is returning from inserting author and we should append it to the form
			if(isSet($session["authorId"])) {
				$session["numOfAuthors"] = $session["numOfAuthors"] + 1;
				$values["authors"][($session["numOfAuthors"]-1)] = $session["authorId"]; // -1 because of indexing from 0
				unset($session["authorId"]);
			}
			$session["values"] = $values;
		}
		// Workaround to unset number of authors on new inserting
		if($this->phase == 2) {
			$session["numOfAuthors"] = 1;
		}
		$this->numOfAuthors = $session["numOfAuthors"];
	}

	public function  __destruct() {
		$session = Environment::getSession("insertingBook");
		$session["numOfAuthors"] = $this->numOfAuthors;
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

	public function handleContinueToInsert($title) {
	    $this->title = $title;
	    $this->phase = 3;
	}

	public function searchFormSubmitted(Form $form) {
		$values = $form->getValues();

		$this->getComponent("bookList")->setSource(
			Leganto::books()->getSelector()
				->searchByColumn(BookSelector::BOOK, $values["book_title"])
		);
		$this->getTemplate()->searchedTitle = $values["book_title"];
		$this->phase = 2;
	}

	/**
	 * It sets book which the inserted book is connected to
	 * @param $book Book entity
	 */
	public function setBook(BookEntity $book) {
	    $this->bookNode = $book->bookNode;
	}

	public function insertFormSubmitted(Form $form) {
		// Workaround: When user ends inserting book, he/she is redirected to phase 3... However when he/she clicks on Add/Remove author component is in phase 1!
		$this->phase = 3;
		$values = $form->getValues();
		if(empty($values["id_book"]) && $form["newAuthor"]->isSubmittedBy()) {
			$session = Environment::getSession("insertingBook");
			$session["values"] = $values;
			$session["numOfAuthors"] = $this->numOfAuthors;
			$this->getPresenter()->redirect("Author:insert");
		} else
		if($form["insert"]->isSubmittedBy()){
			// Insert book
			$book= Leganto::books()->createEmpty();
			$book->languageId = $values["language"];
			$book->title = $values["book_title"];
			$book->subtitle = $values["book_subtitle"];
			$book->inserted = new DateTime;
			if (!empty($values["id_book"])) {
			    $book->bookNode = $values["id_book"];
			}
			$book->persist();

			if (empty($values["id_book"])) {
			    // Insert authors
			    $authors = Leganto::authors()->fetchAndCreateAll(
				    Leganto::authors()->getSelector()->findAll()
					    ->where("id_author IN %l", $values["authors"])
			    );
			    Leganto::books()->getUpdater()->setWrittenBy($book, $authors);
			}
			// Find edition and image
			try {
				$language = Leganto::languages()->getSelector()->find($book->languageId);
				$imageFinder    = new EditionImageFinder();
				$storage        = new EditionImageStorage();
				$googleFinder   = new GoogleBooksBookFinder($language->google);
				$info           = $googleFinder->get($book);
				if (empty($info)) {
					echo "No info found.";
				} else {
					// Get edition
					$editions       = Leganto::editions()->getInserter()->insertByGoogleBooksInfo($book, $info);
					// Try to find image foreach edition
					foreach($editions AS $edition) {
						// Find images
						$images = $imageFinder->get($edition);
						// Store first one
						if (!empty($images)) {
							$storage->store($edition, new File(ExtraArray::firstValue($images)));
						}
					}
				}
			}
			catch(Exception $e) {
				$this->getPresenter()->flashMessage(System::translate("The book has been inserted. Unfortunate, book cover and other additional informations could not be fetched, still you can add them manually."),'info');
			}
			$this->getPresenter()->redirect("Book:default",$book->getId());

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
		if (empty($this->bookNode)) {
		    $form->addGroup("Authors");
		    $authors = array(NULL => "---- " . System::translate("Choose author") . " ----") + Leganto::authors()->getSelector()->findAll()->orderBy("full_name")->fetchPairs("id_author", "full_name");
		    $container = $form->addContainer("authors");



		    for($i=0; $i < $this->numOfAuthors; $i++) {
			    $last = $container->addSelect($i, "Author", $authors)
				    ->skipFirst()
				    ->addRule(Form::FILLED,"Choose the author of the book.");
		    }
		    if(isSet($last)) {
			    // Add text saying what to do
			    $el = Html::el("span")->setClass("underForm");
			    $el->setText(System::translate("If the author is not listed, please click on button New."));
			    $last->setOption("description", $el);
		    }
		    $form->addSubmit("addAuthor","Add")
			    ->getControlPrototype()->setId("addAuthor");
		    // TODO: skryt pri poctu autoru = 1
		    $form->addSubmit("removeAuthor","Remove")
			    ->setValidationScope(FALSE)
			    ->getControlPrototype()->setId("removeAuthor");

		    $form->addSubmit("newAuthor","New")
			    ->setValidationScope(FALSE)
			    ->getControlPrototype()->setId("newAuthor");

		    $form["removeAuthor"]->getControlPrototype()->setId("removeAuthor");
		}
		// Language
		$form->addGroup("Other");
		$languages = Leganto::languages()->getSelector()->findAll()->fetchPairs("id_language", "name");
		$form->addSelect("language", "Language", $languages);

		// Book node
		$form->addHidden("id_book");

		// Submit button
		$form->addGroup();
		$form->addSubmit("insert","Insert book");
		$form->onSubmit[] = array($this,"insertFormSubmitted");

		// Defaults
		$defaults = array();
		$defaults["language"] =System::domain()->idLanguage;
		if (!empty($this->title)) {
		    $defaults["book_title"] = $this->title;
		}
		if (!empty($this->bookNode)) {
		    $defaults["id_book"] = $this->bookNode;
		}
		$form->setDefaults($defaults);
		// Check if there are data in session (user probably returns from adding author) and restore
		$session = Environment::getSession("insertingBook");
		if(isSet($session["values"])) {
			$form->setDefaults($session["values"]);
			unset($session["values"]);
		}

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

