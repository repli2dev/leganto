<?php

class Web_BookPresenter extends Web_BasePresenter {

	private $book;

	public function renderDefault($book, $edition = NULL) {
		$this->getTemplate()->book = $this->getBook();

		if ($edition) {
			$this->getTemplate()->edition = Leganto::editions()->getSelector()->find($edition);
		}

		$this->getTemplate()->authors = Leganto::authors()->fetchAndCreateAll(
					Leganto::authors()->getSelector()
					->findAllByBook($this->getTemplate()->book),
				"Load"
		);

		$this->getTemplate()->tags = Leganto::tags()->fetchAndCreateAll(
					Leganto::tags()->getSelector()
					->findAllByBook($this->getTemplate()->book)
		);

		$storage = new EditionImageStorage();
		$this->getTemplate()->cover = $storage->getRandomFileByBook($this->getTemplate()->book);

		$this->getTemplate()->editions = Leganto::editions()->fetchAndCreateAll(
				Leganto::editions()->getSelector()->findAllByBook($this->getTemplate()->book)
		);
		$this->getComponent("opinionList")->setLimit(5);
		$this->getComponent("opinionList")->showPaginator(FALSE);
		$this->getComponent("opinionList")->setSource(
				Leganto::opinions()->getSelector()
				->findAllByBook($this->getTemplate()->book)
				->where("[content] != ''")
				->applyLimit(5)
		);
		$this->setPageTitle($this->getTemplate()->book->title);
	}

	public function actionAddEdition($book) {
		if (!Environment::getUser()->isAuthenticated()) {
			$this->redirect("Default:unauthorized");
		} else {
			$this->getTemplate()->book = $this->getBook();
			$this->setPageTitle(System::translate("Add edition"));
		}
	}

	public function actionEditEdition($book, $edition) {
		if (!Environment::getUser()->isAuthenticated()) {
			$this->redirect("Default:unauthorized");
		} else {
			$this->setPageTitle(System::translate("Edit edition"));
		}
	}

	public function actionAddOpinion($book) {
		if (!Environment::getUser()->isAuthenticated()) {
			$this->redirect("Default:unauthorized");
		} else {
			$this->getTemplate()->book = $this->getBook();
			$this->setPageTitle(System::translate("Your opinion"));
		}
	}


	public function renderInsert($book) {
		if (!Environment::getUser()->isAuthenticated()) {
			$this->redirect("Default:unauthorized");
		} else {
			$this->setPageTitle(System::translate("Insert book"));
		}
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

	protected function createComponentInsertingOpinion($name) {
		return new InsertingOpinionComponent($this, $name);
	}

	protected function createComponentOpinionList($name) {
		return new OpinionListComponent($this, $name);
	}

	protected function createComponentSimilarBooks($name) {
		return new BookListComponent($this, $name);
	}

	protected function createComponentSubmenu($name) {
		$submenu = new SubmenuComponent($this, $name);
		$submenu->addLink("default", System::translate("General info"), $this->getBook()->getId());
		$submenu->addLink("opinions", System::translate("Opinions"), $this->getBook()->getId());
		$submenu->addLink("similar", System::translate("Similar books"), $this->getBook()->getId());
		if (Environment::getUser()->isAuthenticated()) {
			if(Leganto::opinions()->getSelector()->findByBookAndUser($this->getTemplate()->book, System::user()) == NULL) {
				$submenu->addEvent("addOpinion", System::translate("Add opinion"), $this->getBook()->getId());
			} else {
				$submenu->addEvent("addOpinion", System::translate("Add opinion"), $this->getBook()->getId());
			}
			$submenu->addEvent("addEdition", System::translate("Add new edition"), $this->getBook()->getId());
			$edition = $this->getParam("edition");
			if(!empty($edition)) {
				$submenu->addEvent("editEdition", System::translate("Edit this edition"), array("book" => $this->getBook()->getId(), "edition" => $edition));
			}
		}
		return $submenu;
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