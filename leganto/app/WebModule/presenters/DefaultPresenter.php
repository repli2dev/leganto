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
class Web_DefaultPresenter extends Web_BasePresenter {

	private $firstTime;

	public function renderDefault($login = FALSE) {
		if (Environment::getUser()->isAuthenticated()) {
			$this->forward("feed");
		}
		if($login) {
			$component = $this->getComponent("introduction");
			$component->handleChangeState("login");
		}
		$this->setPageTitle(System::translate("Main page"));
		$this->setPageDescription(System::translate("Leganto is a community webpage which aims to create and share information about literature as a valuable part of our culture. Join our efforts."));
		$this->setPageKeywords(System::translate("books, how to choose book, what friends reads, what my, culture, about books, opinions on books, leganto, čtenáři, preader"));
	}

	public function renderFeed($firstTime = FALSE) {
		if (!Environment::getUser()->isAuthenticated()) {
			$this->forward("default");
		}
		$this->setPageTitle(System::translate("News"));
		$this->firstTime = $firstTime;
		$this->setPageDescription(System::translate("The feed page offers you updates from our web, you can watch added opinions or the discussion in one page."));
		$this->setPageKeywords(System::translate("feed, wall, activity of users, followers."));
	}

	public function renderTopBooks($limit = 200) {
		$books = Leganto::books()->getSelector()->findAllTop()->applyLimit($limit);
		$this->setPageTitle(System::translate("Top books"));
		$this->getComponent("topBookList")->setLimit(18);
		$this->getComponent("topBookList")->setSource($books);
	}

	public function renderUnauthorized() {
		$this->setPageTitle(System::translate("Unauthorized"));
		$this->setPageDescription(System::translate("Sorry, but you have tried to access a protected page, please log in."));
		$this->setPageKeywords(System::translate("error, unauthorized"));
	}

	protected function createComponentIntroduction($name) {
		return new IntroductionComponent($this, $name);
	}

	protected function createComponentPreview($name) {
		return new PreviewComponent($this, $name);
	}

	protected function createComponentFeed($name) {
		$c = new FeedComponent($this, $name);
		$c->firstTime = $this->firstTime;
		return $c;
	}

	protected function createComponentTopBookList($name) {
		return new BookListComponent($this, $name);
	}

}
