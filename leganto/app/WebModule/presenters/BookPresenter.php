<?php

class Web_BookPresenter extends Web_BasePresenter {

	private $book;

	public function renderDefault($book, $edition = NULL) {
		// Opinions
		$this->getComponent("opinionList")->setLimit(5);
		$this->getComponent("opinionList")->showPaginator(FALSE);
		$this->getComponent("opinionList")->setSource(
				Leganto::opinions()->getSelector()
				->findAllByBook($this->getBook())
				->where("[content] != ''")
				->applyLimit(5)
		);
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
		$this->setPageTitle(System::translate("General info").": ".$this->getBook()->title);
		$this->setPageDescription(System::translate("Detail of book where you can find most interesting data such as book cover, tags, opinions, editions, related books etc. "));
		$this->setPageKeywords(System::translate("book, detail, graphs, opinions, tags, editions, isbn, pages, shelves, share to social network"));
	}

	public function actionAddEdition($book) {
		if (!Environment::getUser()->isAllowed(Resource::EDITION, Action::INSERT)) {
			$this->unauthorized();
		} else {
			$this->getTemplate()->book = $this->getBook();
			$this->setPageTitle(System::translate("Add edition").": ".$this->getBook()->title);
			$this->setPageDescription(System::translate("On this page you can add new edition to this book"));
			$this->setPageKeywords(System::translate("add, edition, insert"));
		}
	}

	public function actionEditEdition($book, $edition) {
		if (!Environment::getUser()->isAllowed(Resource::EDITION, Action::EDIT)) {
			$this->unauthorized();
		} else {
			$this->setPageTitle(System::translate("Edit edition").": ".$book->title);
			$this->setPageDescription(System::translate("On this page you can edit already inserted edition."));
			$this->setPageKeywords(System::translate("edit, update, edition"));
		}
	}

	public function actionAddOpinion($book) {
		if (!Environment::getUser()->isAllowed(Resource::OPINION, Action::INSERT)) {
			$this->unauthorized();
		} else {
			$this->getTemplate()->book = $this->getBook();
			$this->setPageTitle(System::translate("Your opinion").": ".$this->getBook()->title);
			$this->setPageDescription(System::translate("On this page you can insert or change your opinion on certain book."));
			$this->setPageKeywords(System::translate("opinion, insert, add, book, your opinion"));
		}
	}

	public function renderEdit($book) {
		// Edit book
		$this->getTemplate()->book = $this->getBook();
		if (!Environment::getUser()->isAllowed(Resource::BOOK, Action::EDIT)) {
			$this->unauthorized();
		}
		$this->getComponent("insertingBook")->setBookToEdit($this->getBook());
		$this->setPageTitle(System::translate("Edit book '".$this->getBook()->title."'"));
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
		
		$this->setPageDescription(System::translate("On this page you can insert new book, for adding to your logbook please use Add opinion."));
		$this->setPageKeywords(System::translate("insert, add, new book"));
	}

	public function renderOpinions($book) {
		$this->getTemplate()->book = $this->getBook();
		$this->getComponent("opinionList")->setSource(
				Leganto::opinions()->getSelector()
				->findAllByBook($this->getTemplate()->book, System::user())
				->where("[content] != ''")
		);
		$this->setPageTitle(System::translate("Opinions").": ".$this->getTemplate()->book->title);
		$this->setPageDescription(System::translate("Opinions to certain book from all users, let's choose if it is worth to read!"));
		$this->setPageKeywords(System::translate("opinion, other users, how to decide what to read"));
	}

	public function renderSimilar($book) {
		$this->getTemplate()->book = $this->getBook();
		$this->getComponent("similarBooks")->setLimit(0);
		$this->getComponent("similarBooks")->setSource(
			Leganto::books()->getSelector()->findAllSimilar($this->getTemplate()->book)->applyLimit(12)
		);
		$this->setPageTitle(System::translate("Similar books").": ".$this->getTemplate()->book->title);
		$this->setPageDescription(System::translate("Similar books to certain book, generated according to book tags. Choose what to read from what you have read!"));
		$this->setPageKeywords(System::translate("similar books, tags, how to choose book"));
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
		return new OpinionListComponent($this, $name);
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
		$submenu->addLink("default", System::translate("General info"), $this->getBook()->getId());
		$submenu->addLink("opinions", System::translate("Opinions"), $this->getBook()->getId());
		$submenu->addLink("similar", System::translate("Similar books"), $this->getBook()->getId());
		if (System::user() != NULL) {
			$opinion = Leganto::opinions()->getSelector()->findByBookAndUser($this->getBook(), System::user());
		}
		if (Environment::getUser()->isAllowed(Resource::BOOK, Action::EDIT)) {
			$submenu->addEvent("edit", System::translate("Edit book"), $this->getBook()->getId());
		}
		if (empty($opinion) && Environment::getUser()->isAllowed(Resource::OPINION, Action::INSERT)) {
			$submenu->addEvent("addOpinion", System::translate("Add opinion"), $this->getBook()->getId());
		} else if (!empty($opinion) && Environment::getUser()->isAllowed(Resource::create($opinion), Action::EDIT)) {
			$submenu->addEvent("addOpinion", System::translate("Change opinion"), $this->getBook()->getId());
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