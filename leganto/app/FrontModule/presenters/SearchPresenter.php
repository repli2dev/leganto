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
namespace FrontModule;
use	Leganto\System,
	Leganto\DB\Factory,
	FrontModule\Components\Submenu,
	FrontModule\Components\BookList,
	FrontModule\Components\Search,
	FrontModule\Components\AuthorList,
	FrontModule\Components\PostList,
	FrontModule\Components\UserList,
	FrontModule\Components\HelpList;

class SearchPresenter extends BasePresenter {

	public function renderDefault($query) {
		$this->getTemplate()->title = System::translate("Book search");
		$this->setPageTitle($this->getTemplate()->title);
		$this->setPageDescription(System::translate("Search the text phrase in all books."));
		$this->setPageKeywords(System::translate("search, find, books"));
		if (empty($query)) {
			$this->getTemplate()->message = System::translate("Enter text to search before you start searching!");
			return;
		}
		$source = Factory::book()->getSelector()->search($query);
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
		$this->setPageDescription(System::translate("Search the text phrase in all authors."));
		$this->setPageKeywords(System::translate("author, search, find"));
		$this->setPageTitle($this->getTemplate()->title);
		if (empty($query)) {
			$this->getTemplate()->message = System::translate("Enter text to search before you start searching!");
			return;
		}
		$source = Factory::author()->getSelector()->search($query);
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
		$this->setPageDescription(System::translate("Search the text phrase in all discussions."));
		$this->setPageKeywords(System::translate("discussion, search, find"));
		$this->setPageTitle($this->getTemplate()->title);
		if (empty($query)) {
			$this->getTemplate()->message = System::translate("Enter text to search before you start searching!");
			return;
		}
		$source = Factory::post()->getSelector()->search($query);
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
		$this->setPageDescription(System::translate("Search the text phrase in all users."));
		$this->setPageKeywords(System::translate("discussion, search, find"));
		$this->setPageTitle($this->getTemplate()->title);
		if (empty($query)) {
			$this->getTemplate()->message = System::translate("Enter text to search before you start searching!");
			return;
		}
		$source = Factory::user()->getSelector()->search($query);
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
		$this->setPageDescription(System::translate("Search the text phrase in the whole help section."));
		$this->setPageKeywords(System::translate("help section, search, find"));
		$this->setPageTitle($this->getTemplate()->title);
		if (empty($query)) {
			$this->getTemplate()->message = System::translate("Enter text to search before you start searching!");
			return;
		}
		$source = Factory::supportText()->getSelector()->search($query);
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

	public function renderAllBooks() {
		$source = Factory::book()->getSelector()->findAll()->orderBy("inserted","DESC");
		$this->getComponent("searchList")->setSource($source);
		$this->getTemplate()->numOfAllBooks = $source->count();
		$this->getTemplate()->numOfAllAuthors = Factory::author()->getSelector()->findAll()->count();

		$this->getTemplate()->title = System::translate("All books");
		$this->setPageTitle(System::translate("All books"));
		$this->setPageDescription(System::translate("This is page where you can find all books in the system. So if you really don't know what to read, try this."));
		$this->setPageKeywords(System::translate("book, detail, graphs, opinions, tags, editions, isbn, pages, what to read"));
	}

	protected function createComponentSubmenu($name) {
		$query = $this->getParam("query");
		$submenu = new Submenu($this, $name);
		$submenu->addLink("default", System::translate("Book"), $query,System::translate("Search in books, keywords and authors"));
		$submenu->addLink("author", System::translate("Author"), $query,System::translate("Search only in authors"));
		$submenu->addLink("discussion", System::translate("Discussion"), $query,System::translate("Search in discussion topics and posts"));
		$submenu->addLink("user", System::translate("User"), $query,System::translate("Search in users"));
		$submenu->addLink("help", System::translate("Help"), $query,System::translate("Search in content of help pages"));
		return $submenu;
	}

	// Factory
	protected function createComponentSearchList($name) {
		return new BookList($this, $name);
	}

	protected function createComponentHelpList($name) {
		return new HelpList($this, $name);
	}

	protected function createComponentAuthorList($name) {
		return new AuthorList($this, $name);
	}

	protected function createComponentPostList($name) {
		return new PostList($this, $name);
	}

	protected function createComponentUserList($name) {
		return new UserList($this, $name);
	}

	protected function createComponentSearchForm($name) {
		return new Search($this, $name, FALSE);
	}

}
