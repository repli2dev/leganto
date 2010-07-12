<?php

class Web_AuthorPresenter extends Web_BasePresenter {

	public function renderInsert($hash = NULL) {
		if (!Environment::getUser()->isAuthenticated()) {
			$this->redirect("Default:unauthorized");
		} else {
			$this->setPageTitle(System::translate("Insert author"));
		}
	}

	protected function createComponentInsertingAuthor($name) {
		return new InsertingAuthorComponent($this, $name);
	}

}