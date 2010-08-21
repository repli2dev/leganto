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
		$this->setPageTitle($this->getBook()->title);
	}

	public function actionAddEdition($book) {
		if (!Environment::getUser()->isAllowed(Resource::EDITION, Action::INSERT)) {
			$this->unauthorized();
		} else {
			$this->getTemplate()->book = $this->getBook();
			$this->setPageTitle(System::translate("Add edition"));
		}
	}

	public function actionEditEdition($book, $edition) {
		if (!Environment::getUser()->isAllowed(Resource::EDITION, Action::EDIT)) {
			$this->unauthorized();
		} else {
			$this->setPageTitle(System::translate("Edit edition"));
		}
	}

	public function actionAddOpinion($book) {
		if (!Environment::getUser()->isAllowed(Resource::OPINION, Action::INSERT)) {
			$this->unauthorized();
		} else {
			$this->getTemplate()->book = $this->getBook();
			$this->setPageTitle(System::translate("Your opinion"));
		}
	}


	public function renderInsert($book, $related = FALSE) {
		if ($related) {
			if (!Environment::getUser()->isAllowed(Resource::BOOK, Action::INSERT)) {
				$this->unauthorized();
			}
			// Insert related book
			$this->getComponent("insertingBook")->setRelatedBook($this->getBook());
		}
		else {
			if (!Environment::getUser()->isAllowed(Resource::BOOK, Action::INSERT)) {
				$this->unauthorized();
			}
			// Edit book
			if (!empty($book)) {
				if (!Environment::getUser()->isAllowed(Resource::BOOK, Action::EDIT)) {
					$this->unauthorized();
				}
				$this->getComponent("insertingBook")->setBookToEdit($this->getBook());
			}
		}
		$this->setPageTitle(System::translate("Insert book"));
	}

	public function renderOpinions($book) {
		$this->getTemplate()->book = $this->getBook();
		$this->getComponent("opinionList")->setSource(
				Leganto::opinions()->getSelector()
				->findAllByBook($this->getTemplate()->book, System::user())
				->where("[content] != ''")
		);
		$this->setPageTitle($this->getTemplate()->book->title);
	}

	public function renderSimilar($book) {
		$this->getTemplate()->book = $this->getBook();
		$this->getComponent("similarBooks")->setLimit(0);
		$this->getComponent("similarBooks")->setSource(
				Leganto::books()->getSelector()->findAllSimilar($this->getTemplate()->book)->applyLimit(12)
		);
		$this->setPageTitle($this->getTemplate()->book->title);
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
			$submenu->addEvent("insert", System::translate("Edit book"), $this->getBook()->getId());
		}
		if (empty($opinion) && Environment::getUser()->isAllowed(Resource::OPINION, Action::INSERT)) {
			$submenu->addEvent("addOpinion", System::translate("Add opinion"), $this->getBook()->getId());
		}
		else if (!empty($opinion) && Environment::getUser()->isAllowed(Resource::create($opinion), Action::EDIT)) {
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
		if(isSet($cache[md5($term)])) {
			echo json_encode($cache[md5($term)]);
		} else {
			$results = array();
			$items = Leganto::books()->getSelector()->suggest($term)->select("title")->applyLimit(10)->fetchAssoc("title");
			foreach($items as $item) {
				$results[] = $item->title;
			}
			echo json_encode($results);
			$cache->save(md5($term), $results, array(
					'expire' => time() + 60 * 60 * 6,	// expire in 6 hours
			));
		}
		die; // Fast respond needed, not want to overload server
	}

	public function renderTagSuggest($term) {
		$cache = Environment::getCache("tagSuggest");
		if(isSet($cache[md5($term)])) {
			echo json_encode($cache[md5($term)]);
		} else {
			$items = Leganto::tags()->getSelector()->suggest($term)->select("name")->applyLimit(10)->fetchAssoc("name");
			foreach($items as $item) {
				$results[] = $item->name;
			}
			echo json_encode($results);
			$cache->save(md5($term), $results, array(
					'expire' => time() + 60 * 60 * 6,	// expire in 6 hours
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