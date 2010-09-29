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
class Web_AuthorPresenter extends Web_BasePresenter {

	/** @var AuthorEntity */
	private $author;

	public function actionDelete($author) {
		if (!Environment::getUser()->isAllowed(Resource::AUTHOR, Action::EDIT)) {
			$this->unauthorized();
		}
		if (Leganto::books()->getSelector()->findAllByAuthor($this->getAuthor())->count() != 0) {
			$this->flashMessage(System::translate("The author can not be deleted, because the there are some books which are written by this author."), "error");
			$this->redirect("default", $author);
		}
		try {
			$this->getAuthor()->delete();
			$this->flashMessage(System::translate("The author has been successfuly deleted."), "success");
		} catch (Exception $e) {
			$this->unexpectedError($e);
		}
		$this->redirect("Default:default");
	}

	public function renderInsert($author = NULL, $editingBook = NULL) {
		if (empty($author) && !Environment::getUser()->isAllowed(Resource::AUTHOR, Action::INSERT)) {
			$this->unauthorized();
		} else if (!empty($author) && !Environment::getUser()->isAllowed(Resource::AUTHOR, Action::EDIT)) {
			$this->unauthorized();
		} else {
			if (!empty($author)) {
				$this->getTemplate()->author = $this->getAuthor();
				$this->getComponent("insertingAuthor")->setAuthor($this->getAuthor());
				$this->getComponent("insertingAuthor")->setBacklink("default", $author);
				$this->setPageTitle(System::translate("Edit author"));
				$this->setPageDescription(System::translate("On this page, you can edit already inserted author."));
				$this->setPageKeywords(System::translate("edit, author, change"));
			} else {
				if (empty($editingBook)) {
					$this->getComponent("insertingAuthor")->setBacklink("Book:insert");
				} else {
					$this->getComponent("insertingAuthor")->setBacklink("Book:edit", $editingBook);
				}
				$this->setPageTitle(System::translate("Insert author"));
				$this->setPageDescription(System::translate("On this page, you can insert new author. Please double check if author do not exist."));
				$this->setPageKeywords(System::translate("insert, author, new"));
			}
		}
	}

	public function renderDefault($author) {
		$this->getTemplate()->author = $this->getAuthor();
		$this->getComponent("bookList")->setLimit(0);
		$this->getComponent("bookList")->setSource(
			Leganto::books()->getSelector()->findAllByAuthor($this->getAuthor())->applyLimit(12)
		);
		$this->setPageTitle($this->getTemplate()->author->fullname);
		$this->setPageDescription(System::translate("Detail page of author, shows all books from this author, which had been already inserted on our website."));
		$this->setPageKeywords(System::translate("detail, author, books of author"));
	}

	public function renderSuggest($term) {
		$cache = Environment::getCache("authorSuggest");
		if (isSet($cache[md5($term)])) {
			echo json_encode($cache[md5($term)]);
		} else {
			$items = Leganto::authors()->getSelector()->suggest($term)->select("full_name")->applyLimit(10)->fetchAssoc("full_name");
			foreach ($items as $item) {
				$results[] = $item->full_name;
			}
			echo json_encode($results);
			$cache->save(md5($term), $results, array(
			    'expire' => time() + 60 * 60 * 6, // expire in 6 hours
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
		$submenu->addLink("default", System::translate("Books"), $this->getAuthor()->getId());
		if (Environment::getUser()->isAllowed(Resource::AUTHOR, Action::EDIT)) {
			$submenu->addEvent("insert", System::translate("Edit author"), $this->getAuthor()->getId());
			$submenu->addEvent("delete", System::translate("Delete author"), $this->getAuthor()->getId(), System::translate("Are you sure you want to delete this author?"));
		}
		return $submenu;
	}

	protected function createComponentBookList($name) {
		return new BookListComponent($this, $name);
	}

	// ---- PRIVATE METHODS
	private function getAuthor() {
		if (!isset($this->author)) {
			if ($this->getParam("author") != NULL) {
				$this->author = Leganto::authors()->getSelector()->find($this->getParam("author"));
			} else {
				$this->author = NULL;
			}
		}
		return $this->author;
	}

}