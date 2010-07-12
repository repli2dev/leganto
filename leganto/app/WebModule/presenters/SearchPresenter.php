<?php
/**
 * The source file is subject to the license located on web
 * "http://code.google.com/p/preader/".
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (repli2dev@gmail.com)
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
		if(empty($query)){
			$this->getTemplate()->message = System::translate("Enter text to search before you start searching!");
			return;
		}
		$source = Leganto::books()->getSelector()->search($query);
		// If there is only one result then redirect to it
		$count = $source->count();
		if($count == 0){
			$this->getTemplate()->message = System::translate("Nothing was found for your query, please be less specific.");
			return;
		}
		if($count == 1) {
			$row = $source->fetch();

			$this->redirect('Book:Default',$row->id_book);
		}
		$this->getComponent("searchList")->setSource($source);
	}

	public function renderAuthor($query) {
		$this->getTemplate()->title = System::translate("Author search");
		$this->setPageTitle($this->getTemplate()->title);
		if(empty($query)){
			$this->getTemplate()->message = System::translate("Enter text to search before you start searching!");
			return;
		}
		$source = Leganto::authors()->getSelector()->search($query);
		$count = $source->count();
		if($count == 0){
			$this->getTemplate()->message = System::translate("Nothing was found for your query, please be less specific.");
			return;
		}
		// TODO: pridat presmerovani pokud je vysledek jeden
		$this->getComponent("authorList")->setSource($source);
	}

	public function renderDiscussion($query) {
		$this->getTemplate()->title = System::translate("Discussion search");
		$this->setPageTitle($this->getTemplate()->title);
		if(empty($query)){
			$this->getTemplate()->message = System::translate("Enter text to search before you start searching!");
			return;
		}
		$source = Leganto::posts()->getSelector()->search($query);
		$count = $source->count();
		if($count == 0){
			$this->getTemplate()->message = System::translate("Nothing was found for your query, please be less specific.");
			return;
		}
		// TODO: pridat presmerovani pokud je vysledek jeden
		$this->getComponent("postList")->setSource($source);
	}

	public function renderUser($query) {
		$this->getTemplate()->title = System::translate("User search");
		$this->setPageTitle($this->getTemplate()->title);
		if(empty($query)){
			$this->getTemplate()->message = System::translate("Enter text to search before you start searching!");
			return;
		}
		$source = Leganto::users()->getSelector()->search($query);
		$count = $source->count();
		if($count == 0){
			$this->getTemplate()->message = System::translate("Nothing was found for your query, please be less specific.");
			return;
		}
		// TODO: pridat presmerovani pokud je vysledek jeden
		$this->getComponent("userList")->setSource($source);
	}

	protected function createComponentSubmenu($name) {
		$query = $this->getParam("query");
		$submenu = new SubmenuComponent($this, $name);
		$submenu->addLink("default", System::translate("Book search"), $query);
		$submenu->addLink("author", System::translate("Author search"), $query);
		$submenu->addLink("discussion", System::translate("Discussion search"), $query);
		$submenu->addLink("user", System::translate("User search"), $query);
		return $submenu;
	}

	// Factory
	protected function createComponentSearchList($name) {
		return new BookListComponent($this, $name);
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
		return new SearchComponent($this, $name,FALSE);
	}

}
