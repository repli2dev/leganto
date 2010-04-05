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
class Web_DefaultPresenter extends Web_BasePresenter {
	public function renderDefault() {
		$this->setPageTitle(System::translate("Main page"));
		if(Environment::getUser()->isAuthenticated()) {
			$this->forward("feed");
		}
	}
	
	public function renderFeed() {
		$this->setPageTitle(System::translate("News"));
		$this->getComponent("feed")->setUp(Leganto::feed()->getSelector()->findAll());
	}

	public function renderSearch($query, $book = TRUE) {
	    $this->setPageTitle(System::translate("Search"));
	    $source = Leganto::books()->getSelector()->search($query);
	    $this->getComponent("searchList")->setUp($source);
	}

	protected function createComponentIntroduction($name) {
		return new IntroductionComponent($this,$name);
	}

	protected function createComponentPreview($name) {
		return new PreviewComponent($this,$name);
	}

	protected function createComponentSearchList($name) {
	    return new BookListComponent($this, $name);
	}

	protected function createComponentFeed($name) {
	    return new FeedComponent($this, $name);
	}

}
