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
class Web_BookPresenter extends Web_BasePresenter {

	private $book;

	public function renderDefault($book, $edition = NULL) {
		// Opinions
		$opinions = Leganto::opinions()->getSelector()
			->findAllByBook($this->getBook())
			->where("[content] != ''")
			->applyLimit(5);
		$this->getComponent("opinionList")->setLimit(5);
		$this->getComponent("opinionList")->showPaginator(FALSE);
		$this->getComponent("opinionList")->setSource($opinions);
		$this->getTemplate()->opinionCount = $opinions->count();
		$this->getTemplate()->bookId = $this->getBook()->getId();
		// Editions
		$this->getComponent("editionList")->setSource(
			Leganto::editions()->getSelector()->findAllByBook($this->getBook())
		);
		// Related books
		$this->getComponent("relatedBookList")->setSource(
			Leganto::books()->getSelector()->findAllRelated($this->getBook())
		);
		// Edition?
		$this->getComponent("bookView")->setEditionId($edition);
		// Page title
		$this->setPageTitle(System::translate("General info") . ": " . $this->getBook()->title);
		$this->setPageDescription(System::translate("This is the detail of the book where you can find the most interesting data such as the book cover, tags, opinions, editions, related books etc."));
		$this->setPageKeywords(System::translate("book, detail, graphs, opinions, tags, editions, isbn, pages, shelves, share to social network"));
	}

	public function actionAddEdition($book) {
		if (!Environment::getUser()->isAllowed(Resource::EDITION, Action::INSERT)) {
			$this->unauthorized();
		} else {
			$this->getTemplate()->book = $this->getBook();
			$this->setPageTitle(System::translate("Add edition") . ": " . $this->getBook()->title);
			$this->setPageDescription(System::translate("You can add a new edition of this book on this page."));
			$this->setPageKeywords(System::translate("add, edition, insert"));
		}
	}

	public function actionEditEdition($book, $edition) {
		if (!Environment::getUser()->isAllowed(Resource::EDITION, Action::EDIT)) {
			$this->unauthorized();
		} else {
			$this->setPageTitle(System::translate("Edit edition") . ": " . $this->getBook()->title);
			$this->setPageDescription(System::translate("You can edit an already inserted edition on this page."));
			$this->setPageKeywords(System::translate("edit, update, edition"));
		}
	}

	public function actionAddOpinion($book) {
		if (!Environment::getUser()->isAllowed(Resource::OPINION, Action::INSERT)) {
			$this->unauthorized();
		} else {
			$this->getTemplate()->book = $this->getBook();
			$this->setPageTitle(System::translate("Your opinion") . ": " . $this->getBook()->title);
			$this->setPageDescription(System::translate("You can insert or change your opinion on a certain book on this page."));
			$this->setPageKeywords(System::translate("opinion, insert, add, book, your opinion"));
		}
	}

	public function actionRandom() {
		$this->redirect("default",Leganto::books()->getSelector()->findRandom()->id_book_title);
	}

	public function renderEdit($book) {
		// Edit book
		$this->getTemplate()->book = $this->getBook();
		if (!Environment::getUser()->isAllowed(Resource::BOOK, Action::EDIT)) {
			$this->unauthorized();
		}
		$this->getComponent("insertingBook")->setBookToEdit($this->getBook());
		$this->setPageTitle(System::translate("Edit the book")." '".$this->getBook()->title."'");
	}

	public function renderInsert($book, $related = FALSE) {
		if ($related) {
			if (!Environment::getUser()->isAllowed(Resource::BOOK, Action::INSERT)) {
				$this->unauthorized();
			}
			// Insert related book
			$this->getComponent("insertingBook")->setRelatedBook($this->getBook());
			$this->setPageTitle(System::translate("Insert book"));
		} else {
			if (!Environment::getUser()->isAllowed(Resource::BOOK, Action::INSERT)) {
				$this->unauthorized();
			}
			// Insert a new book
			$this->setPageTitle(System::translate("Insert book"));
		}

		$this->setPageDescription(System::translate("You can insert a new book on this page. To add an opinion to an already inserted book, please use 'Add opinion'."));
		$this->setPageKeywords(System::translate("insert, add, new book"));
	}

	public function renderOpinions($book) {
		$this->getTemplate()->book = $this->getBook();
		$opinions = Leganto::opinions()->getSelector()
				->findAllByBook($this->getTemplate()->book, System::user())
				->where("[content] IS NOT NULL AND LENGTH(TRIM([content])) > 0");
		$this->getComponent("opinionList")->setSource($opinions);
		$this->getTemplate()->opinionCount = $opinions->count();
		$this->setPageTitle(System::translate("Opinions") . ": " . $this->getTemplate()->book->title);
		$this->setPageDescription(System::translate("Opinions on a certain book from all users, decide if it is worth reading!"));
		$this->setPageKeywords(System::translate("opinion, other users, how to decide what to read"));
	}

	public function renderSimilar($book) {
		$this->getTemplate()->book = $this->getBook();
		$this->getComponent("similarBooks")->setLimit(0);
		$this->getComponent("similarBooks")->setSource(
			Leganto::books()->getSelector()->findAllSimilar($this->getTemplate()->book)->applyLimit(12)
		);
		$this->setPageTitle(System::translate("Similar books") . ": " . $this->getTemplate()->book->title);
		$this->setPageDescription(System::translate("Similar books to a certain book, generated according to book tags. Choose what to read according to what you have read!"));
		$this->setPageKeywords(System::translate("similar books, tags, how to choose book"));
	}

	protected function createComponentBookMerger($name) {
		$merger = new BookMergerComponent($this, $name);
		$merger->setBook($this->getBook());
		return $merger;
	}
 
	protected function createComponentBookView($name) {
		$view = new BookViewComponent($this, $name);
		$view->setBook($this->getBook());
		return $view;
	}

	protected function createComponentBookShelfControl($name) {
		$component = new BookShelfControlComponent($this, $name);
		$component->setBook($this->getBook());
		return $component;
	}

	protected function createComponentInsertingBook($name) {
		return new InsertingBookComponent($this, $name);
	}

	protected function createComponentEditionForm($name) {
		return new EditionComponent($this, $name);
	}

	protected function createComponentEditionList($name) {
		return new EditionListComponent($this, $name);
	}

	protected function createComponentInsertingOpinion($name) {
		return new InsertingOpinionComponent($this, $name);
	}

	protected function createComponentOpinionList($name) {
		$list =  new OpinionListComponent($this, $name);
		if ($this->getAction() == "opinions") {
			$list->showSorting();
		}
		return $list;
	}

	protected function createComponentRelatedBookList($name) {
		return new RelatedBookListComponent($this, $name);
	}

	protected function createComponentSimilarBooks($name) {
		return new BookListComponent($this, $name);
	}

	protected function createComponentShareBox($name) {
		return new ShareBoxComponent($this, $name);
	}

	protected function createComponentSubmenu($name) {
		$submenu = new SubmenuComponent($this, $name);
		$submenu->addLink("default", System::translate("General info"), $this->getBook()->getId(),System::translate("Show authors, editions, graphs and few opinions."));
		$submenu->addLink("opinions", System::translate("Opinions"), $this->getBook()->getId(),System::translate("What other users say about this book"));
		$submenu->addLink("similar", System::translate("Similar books"), $this->getBook()->getId(),System::translate("Similar books according tags"));
		$submenu->addLink("Search:allBooks", System::translate("All books"));
		$submenu->addLink("random", System::translate("Random book"),NULL,System::translate("Bored? Click to get random book."));
		if (System::user() != NULL) {
			$opinion = Leganto::opinions()->getSelector()->findByBookAndUser($this->getBook(), System::user());
		}
		if (empty($opinion) && Environment::getUser()->isAllowed(Resource::OPINION, Action::INSERT)) {
			$submenu->addEvent("addOpinion", System::translate("Add opinion"), $this->getBook()->getId());
		} else if (!empty($opinion) && Environment::getUser()->isAllowed(Resource::create($opinion), Action::EDIT)) {
			$submenu->addEvent("addOpinion", System::translate("Change opinion"), $this->getBook()->getId());
		}
		if (Environment::getUser()->isAllowed(Resource::BOOK, Action::EDIT)) {
			$submenu->addEvent("edit", System::translate("Edit book"), $this->getBook()->getId());
		}
		if (Environment::getUser()->isAllowed(Resource::BOOK, Action::INSERT)) {
			$submenu->addevent("insert", System::translate("Insert related book"), array("book" => $this->getBook()->getId(), "related" => TRUE));
		}
		if (Environment::getUser()->isAllowed(Resource::EDITION, Action::INSERT)) {
			$submenu->addEvent("addEdition", System::translate("Add new edition"), $this->getBook()->getId());
		}
		$edition = $this->getComponent("bookView")->getEditionId();
		if (Environment::getUser()->isAllowed(Resource::EDITION, Action::EDIT) && !empty($edition)) {
			$submenu->addEvent("editEdition", System::translate("Edit this edition"), array("book" => $this->getBook()->getId(), "edition" => $edition));
		}

		return $submenu;
	}

	protected function createComponentTagList($name) {
		$tags = new TagListComponent($this, $name);
		$tags->setBook($this->getBook());
		$tags->setSource(Leganto::tags()->getSelector()->findAllByBook($this->getBook()));
		return $tags;
	}

	public function renderSuggest($term) {
		$cache = Environment::getCache("bookSuggest");
		if (isSet($cache[md5($term)])) {
			echo json_encode($cache[md5($term)]);
		} else {
			$results = array();
			$items = Leganto::books()->getSelector()->suggest($term)->select("title")->applyLimit(10)->fetchAssoc("title");
			foreach ($items as $item) {
				$results[] = $item->title;
			}
			echo json_encode($results);
			$cache->save(md5($term), $results, array(
			    'expire' => time() + 60 * 60 * 6, // expire in 6 hours
			));
		}
		die; // Fast respond needed, not want to overload server
	}

	public function renderTagSuggest($term) {
		$cache = Environment::getCache("tagSuggest");
		if (isSet($cache[md5($term)])) {
			echo json_encode($cache[md5($term)]);
		} else {
			$items = Leganto::tags()->getSelector()->suggest($term)->select("name")->applyLimit(10)->fetchAssoc("name");
			foreach ($items as $item) {
				$results[] = $item->name;
			}
			echo json_encode($results);
			$cache->save(md5($term), $results, array(
			    'expire' => time() + 60 * 60 * 6, // expire in 6 hours
			));
		}
		die; // Fast respond needed, not want to overload server
	}

	// PRIVATE METHODS

	/** @return BookEntity */
	private function getBook() {
		if (empty($this->book)) {
			$this->book = $this->getTemplate()->book = Leganto::books()->getSelector()->find($this->getParam("book"));
		}
		return $this->book;
	}

}