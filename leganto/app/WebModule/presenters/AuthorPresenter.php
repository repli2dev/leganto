<?php

class Web_AuthorPresenter extends Web_BasePresenter {

	public function renderInsert() {
		if (!Environment::getUser()->isAuthenticated()) {
		    $this->redirect("Default:unauthorized");
		} else {
		    $this->getComponent("insertingAuthor")->setBacklink("Book:insert");
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

	public function renderSuggest($term) {
		$cache = Environment::getCache("authorSuggest");
		if(isSet($cache[md5($term)])){
			echo json_encode($cache[md5($term)]);
		} else {
			$items = Leganto::authors()->getSelector()->suggest($term)->select("full_name")->applyLimit(10)->fetchAssoc("full_name");
			foreach($items as $item) {
				$results[] = $item->full_name;
			}
			echo json_encode($results);
			$cache->save(md5($term), $results, array(
				'expire' => time() + 60 * 60 * 6,	// expire in 6 hours
			));
		}
		die; // Fast respond needed, not want to overload server
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