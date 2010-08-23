<?php

/**
 * The source file is subject to the license located on web
 * "http://code.google.com/p/preader/".
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (repli2dev@gmail.com)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 */

/**
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$Id$
 */
class Web_SearchPresenter extends Web_BasePresenter {

	public function renderDefault($query) {
		$this->getTemplate()->title = System::translate("Book search");
		$this->setPageTitle($this->getTemplate()->title);
		$this->setPageDescription(System::translate("Search text phrase in all books."));
		$this->setPageKeywords(System::translate("search, find, books"));
		if (empty($query)) {
			$this->getTemplate()->message = System::translate("Enter text to search before you start searching!");
			return;
		}
		$source = Leganto::books()->getSelector()->search($query);
		// If there is only one result then redirect to it
		$count = $source->count();
		if ($count == 0) {
			$this->getTemplate()->message = System::translate("Nothing was found for your query, please be less specific.");
			return;
		} else
		if ($count == 1) {
			$row = $source->fetch();

			$this->redirect('Book:Default', $row->id_book_title);
		}
		$this->getComponent("searchList")->setSource($source);
	}

	public function renderAuthor($query) {
		$this->getTemplate()->title = System::translate("Author search");
		$this->setPageDescription(System::translate("Search text phrase in all authors."));
		$this->setPageKeywords(System::translate("author, search, find"));
		$this->setPageTitle($this->getTemplate()->title);
		if (empty($query)) {
			$this->getTemplate()->message = System::translate("Enter text to search before you start searching!");
			return;
		}
		$source = Leganto::authors()->getSelector()->search($query);
		$count = $source->count();
		if ($count == 0) {
			$this->getTemplate()->message = System::translate("Nothing was found for your query, please be less specific.");
			return;
		} else
		if ($count == 1) {
			$row = $source->fetch();

			$this->redirect('Author:default', $row->id_author);
		}
		$this->getComponent("authorList")->setSource($source);
	}

	public function renderDiscussion($query) {
		$this->getTemplate()->title = System::translate("Discussion search");
		$this->setPageDescription(System::translate("Search text phrase in all discussions."));
		$this->setPageKeywords(System::translate("discussion, search, find"));
		$this->setPageTitle($this->getTemplate()->title);
		if (empty($query)) {
			$this->getTemplate()->message = System::translate("Enter text to search before you start searching!");
			return;
		}
		$source = Leganto::posts()->getSelector()->search($query);
		$count = $source->count();
		if ($count == 0) {
			$this->getTemplate()->message = System::translate("Nothing was found for your query, please be less specific.");
			return;
		}
		// TODO: pridat presmerovani pokud je vysledek jeden
		$this->getComponent("postList")->setSource($source);
		$this->getComponent("postList")->enableLinks();
		$this->getComponent("postList")->disablePosting();
	}

	public function renderUser($query) {
		$this->getTemplate()->title = System::translate("User search");
		$this->setPageDescription(System::translate("Search text phrase in all users."));
		$this->setPageKeywords(System::translate("discussion, search, find"));
		$this->setPageTitle($this->getTemplate()->title);
		if (empty($query)) {
			$this->getTemplate()->message = System::translate("Enter text to search before you start searching!");
			return;
		}
		$source = Leganto::users()->getSelector()->search($query);
		$count = $source->count();
		if ($count == 0) {
			$this->getTemplate()->message = System::translate("Nothing was found for your query, please be less specific.");
			return;
		} else
		if ($count == 1) {
			$row = $source->fetch();

			$this->redirect('User:default', $row->id_user);
		}
		$this->getComponent("userList")->setSource($source);
	}

	public function renderHelp($query) {
		$this->getTemplate()->title = System::translate("Help search");
		$this->setPageDescription(System::translate("Search text phrase in whole help section."));
		$this->setPageKeywords(System::translate("help section, search, find"));
		$this->setPageTitle($this->getTemplate()->title);
		if (empty($query)) {
			$this->getTemplate()->message = System::translate("Enter text to search before you start searching!");
			return;
		}
		$source = Leganto::supportText()->getSelector()->search($query);
		// If there is only one result then redirect to it
		$count = $source->count();
		if ($count == 0) {
			$this->getTemplate()->message = System::translate("Nothing was found for your query, please be less specific.");
			return;
		} else
		if ($count == 1) {
			$row = $source->fetch();

			$this->redirect('Help:text', $row->id_support_text);
		}
		$this->getComponent("helpList")->setSource($source);
	}

	protected function createComponentSubmenu($name) {
		$query = $this->getParam("query");
		$submenu = new SubmenuComponent($this, $name);
		$submenu->addLink("default", System::translate("Book search"), $query);
		$submenu->addLink("author", System::translate("Author search"), $query);
		$submenu->addLink("discussion", System::translate("Discussion search"), $query);
		$submenu->addLink("user", System::translate("User search"), $query);
		$submenu->addLink("help", System::translate("Help"), $query);
		return $submenu;
	}

	// Factory
	protected function createComponentSearchList($name) {
		return new BookListComponent($this, $name);
	}

	protected function createComponentHelpList($name) {
		return new HelpListComponent($this, $name);
	}

	protected function createComponentAuthorList($name) {
		return new AuthorListComponent($this, $name);
	}

	protected function createComponentPostList($name) {
		return new PostListComponent($this, $name);
	}

	protected function createComponentUserList($name) {
		return new UserListComponent($this, $name);
	}

	protected function createComponentSearchForm($name) {
		return new SearchComponent($this, $name, FALSE);
	}

}
