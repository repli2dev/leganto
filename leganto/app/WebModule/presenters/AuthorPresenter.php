<?php

class Web_AuthorPresenter extends Web_BasePresenter {

	public function renderInsert() {
		if (!Environment::getUser()->isAuthenticated()) {
			$this->redirect("Default:unauthorized");
		} else {
			$this->setPageTitle(System::translate("Insert author"));
		}
	}

	public function renderDefault($author) {
		$this->getTemplate()->author = Leganto::authors()->getSelector()->find($author);
		$this->getComponent("bookList")->setLimit(0);
		$this->getComponent("bookList")->setSource(
			Leganto::books()->getSelector()->findAllByAuthor($this->getTemplate()->author)->applyLimit(12)
		);
		$this->setPageTitle($this->getTemplate()->author->fullname);
	}

	// FACTORY

	protected function createComponentInsertingAuthor($name) {
		return new InsertingAuthorComponent($this, $name);
	}

	protected function createComponentSubmenu($name) {
		$submenu = new SubmenuComponent($this, $name);
		$submenu->addLink("default", System::translate("Books"), array("author" => $this->getTemplate()->author->getId()));
		return $submenu;
	}
	
	protected function createComponentBookList($name) {
		return new BookListComponent($this, $name);
	}

}