<?php

class Web_BookPresenter extends Web_BasePresenter {

	public function renderDefault($book, $edition = NULL) {
		$this->getTemplate()->book = Leganto::books()->getSelector()->find($book);

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
		$this->getComponent("opinionList")->setSource(
				Leganto::opinions()->getSelector()
				->findAllByBook($this->getTemplate()->book)
				->where("[content] != ''")
				->applyLimit(5)
		);
		$this->setPageTitle($this->getTemplate()->book->title);
	}

	public function actionWantRead($book) {
		if (!Environment::getUser()->isAuthenticated()) {
			$this->redirect("Default:unauthorized");
		} else {
			// TODO: Add to shelf
			
		}
	}

	public function actionAddOpinion($book) {
		if (!Environment::getUser()->isAuthenticated()) {
			$this->redirect("Default:unauthorized");
		} else {
			$this->setPageTitle(System::translate("Add opinion"));
		}
	}

	public function renderInsert() {
		if (!Environment::getUser()->isAuthenticated()) {
			$this->redirect("Default:unauthorized");
		} else {
			$this->setPageTitle(System::translate("Insert book"));
		}
	}

	public function renderOpinions($book) {
		$this->getTemplate()->book = Leganto::books()->getSelector()->find($book);
		$this->getComponent("opinionList")->setSource(
				Leganto::opinions()->getSelector()
				->findAllByBook($this->getTemplate()->book, System::user())
				->where("[content] != ''")
		);
		$this->setPageTitle($this->getTemplate()->book->title);
	}

	public function renderSimilar($book) {
		$this->getTemplate()->book = Leganto::books()->getSelector()->find($book);
		$this->getComponent("similarBooks")->setLimit(0);
		$this->getComponent("similarBooks")->setSource(
			Leganto::books()->getSelector()->findAllSimilar($this->getTemplate()->book)->applyLimit(12)
		);
		$this->setPageTitle($this->getTemplate()->book->title);
	}

	protected function createComponentInsertingBook($name) {
		return new InsertingBookComponent($this, $name);
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
		$submenu->addLink("default", System::translate("General info"), array("book" => $this->getTemplate()->book->getId()));
		$submenu->addLink("opinions", System::translate("Opinions"), array("book" => $this->getTemplate()->book->getId()));
		$submenu->addLink("similar", System::translate("Similar books"), array("book" => $this->getTemplate()->book->getId()));
		if (Environment::getUser()->isAuthenticated()) {
			if(Leganto::opinions()->getSelector()->findByBookAndUser($this->getTemplate()->book, System::user()) == NULL) {
				$submenu->addEvent("addOpinion", System::translate("Add opinion"), array("book" => $this->getTemplate()->book->getId()));
			} else {
				$submenu->addEvent("addOpinion", System::translate("Change opinion"), array("book" => $this->getTemplate()->book->getId()));
			}
			$submenu->addEvent("wantRead", System::translate("Want read"), array("book" => $this->getTemplate()->book->getId()));
		}
		return $submenu;
	}

}