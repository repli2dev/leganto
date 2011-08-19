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
use FrontModule\Components\Introduction,
	Leganto\System,
	Leganto\DB\Factory,
	FrontModule\Components\Preview,
	FrontModule\Components\BookList,
	FrontModule\Components\Feed,
	FrontModule\Components\Support;

class DefaultPresenter extends BasePresenter {

	private $firstTime;

	public function renderDefault($login = FALSE) {
		if ($this->user->isLoggedIn()) {
			$this->forward("feed");
		}
		$component = $this->getComponent("introduction");
		if($login && $component->state == "default") {
			$session = $this->getSession("postLogin");
			$session->setExpiration("+30 minutes");
			$session["returnUrl"] = $this->getContext()->httpRequest->getReferer();
			$component->handleChangeState("login");
		}
		$this->setPageTitle($this->translate("Main page"));
		$this->setPageDescription($this->translate("Leganto is a community webpage (of readers) which aims to create and share information about literature as a valuable part of our culture. Join our efforts."));
		$this->setPageKeywords($this->translate("books, how to choose a book, what friends read, culture, about books, book database, virtual library, opinions on books, leganto, čtenáři, reader"));
	}

	public function renderFeed($firstTime = FALSE) {
		if (!$this->user->isLoggedIn()) {
			$this->forward("default");
		}
		$session = $this->getSession("postLogin");
		if(isSet($session["returnUrl"])) {
			$url = $session["returnUrl"];
			$session->remove();
			$this->redirectUri($url);
		}
		// For paginator to work
		if($this->isAjax()) {
			$this->getComponent("feed")->invalidateControl("feed");
		}
		$this->setPageTitle($this->translate("News"));
		$this->firstTime = $firstTime;
		$this->setPageDescription($this->translate("The feed page offers you updates from our web, you can watch added opinions or the discussion in one page."));
		$this->setPageKeywords($this->translate("feed, wall, activity of users, followers, others, friends"));
	}

	public function renderTopBooks($limit = 200) {
		$books = Factory::book()->getSelector()->findAllTop()->applyLimit($limit);
		$this->setPageTitle($this->translate("Top books"));
		$this->getComponent("topBookList")->setLimit(18);
		$this->getComponent("topBookList")->setSource($books);
	}

	public function renderContact() {
		$this->setPageTitle($this->translate("Contact us"));
		$this->setPageDescription($this->translate("Let us know what you like, what is causing you problems... Everything about this webpage."));
		$this->setPageKeywords($this->translate("contact, contact form, contact us, write to us, problem, idea"));
	}

	protected function createComponentIntroduction($name) {
		return new Introduction($this, $name);
	}

	protected function createComponentPreview($name) {
		return new Preview($this, $name);
	}

	protected function createComponentFeed($name) {
		$c = new Feed($this, $name);
		$c->firstTime = $this->firstTime;
		return $c;
	}

	protected function createComponentTopBookList($name) {
		return new BookList($this, $name);
	}
	
	public function createComponentSupport($name) {
		return new Support($this,$name);
	}

}