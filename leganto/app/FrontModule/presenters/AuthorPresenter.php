<?php

/**
 * Author presenter
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace FrontModule;

use Nette\Environment,
    Leganto\DB\Factory,
    FrontModule\Components\Submenu,
    FrontModule\Components\BookList,
    Leganto\System,
    Leganto\ACL\Resource,
    Leganto\ACL\Action,
    Nette\Caching\Cache,
    FrontModule\Components\InsertingAuthor;

class AuthorPresenter extends BasePresenter {

	/** @var AuthorEntity */
	private $author;

	public function actionDelete($author) {
		if (!$this->getUser()->isAllowed(Resource::AUTHOR, Action::EDIT)) {
			$this->unauthorized();
		}
		if (Factory::book()->getSelector()->findAllByAuthor($this->getAuthor())->count() != 0) {
			$this->flashMessage($this->translate("The author can't be deleted, because there are some books which are written by this author."), "error");
			$this->redirect("default", $author);
		}
		try {
			$this->getAuthor()->delete();
			$this->flashMessage($this->translate("The author has been successfuly deleted."), "success");
		} catch (Exception $e) {
			$this->unexpectedError($e);
		}
		$this->redirect("Default:default");
	}

	public function renderInsert($author = NULL, $editingBook = NULL) {
		if (empty($author) && !$this->getUser()->isAllowed(Resource::AUTHOR, Action::INSERT)) {
			$this->unauthorized();
		} else if (!empty($author) && !$this->getUser()->isAllowed(Resource::AUTHOR, Action::EDIT)) {
			$this->unauthorized();
		} else {
			if (!empty($author)) {
				$this->getTemplate()->author = $this->getAuthor();
				$this->getComponent("insertingAuthor")->setAuthor($this->getAuthor());
				$this->getComponent("insertingAuthor")->setBacklink("default", $author);
				$this->setPageTitle($this->translate("Edit author"));
				$this->setPageDescription($this->translate("You can edit an already inserted author on this page."));
				$this->setPageKeywords($this->translate("edit, author, change"));
			} else {
				if (empty($editingBook)) {
					$this->getComponent("insertingAuthor")->setBacklink("Book:insert");
				} else {
					$this->getComponent("insertingAuthor")->setBacklink("Book:edit", $editingBook);
				}
				$this->setPageTitle($this->translate("Insert author"));
				$this->setPageDescription($this->translate("You can insert a new author on this page. Please double check if the author exists."));
				$this->setPageKeywords($this->translate("insert, author, new"));
			}
		}
	}

	public function renderDefault($author) {
		$this->getTemplate()->author = $this->getAuthor();
		$this->getComponent("bookList")->setSource(
			Factory::book()->getSelector()->findAllByAuthor($this->getAuthor())
		);
		$this->setPageTitle($this->getTemplate()->author->fullname);
		$this->setPageDescription($this->translate("This is the detail page about the author, it shows all books by this author, which have been already inserted in our website."));
		$this->setPageKeywords($this->translate("detail, author, books of author"));
	}

	public function renderSuggest($term) {
		$cache = new Cache($this->getService("cacheStorage"), "authorSuggest");
		if (isSet($cache[md5($term)])) {
			echo json_encode($cache[md5($term)]);
		} else {
			$items = Factory::author()->getSelector()->suggest($term)->select("full_name")->applyLimit(10)->fetchAssoc("full_name");
			$results = array();
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
		return new InsertingAuthor($this, $name);
	}

	protected function createComponentSubmenu($name) {
		$submenu = new Submenu($this, $name);
		$submenu->addLink("default", $this->translate("Books"), $this->getAuthor()->getId());
		if ($this->getUser()->isAllowed(Resource::AUTHOR, Action::EDIT)) {
			$submenu->addEvent("insert", $this->translate("Edit author"), $this->getAuthor()->getId());
			$submenu->addEvent("delete", $this->translate("Delete author"), $this->getAuthor()->getId(), $this->translate("Are you sure you want to delete this author?"));
		}
		return $submenu;
	}

	protected function createComponentBookList($name) {
		return new BookList($this, $name);
	}

	// ---- PRIVATE METHODS
	private function getAuthor() {
		if (!isset($this->author)) {
			if ($this->getParam("author") != NULL) {
				$this->author = Factory::author()->getSelector()->find($this->getParam("author"));
			} else {
				$this->author = NULL;
			}
		}
		return $this->author;
	}

}