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
		if(Environment::getUser()->isAuthenticated()) {
			$this->forward("feed");
		}
		$this->setPageTitle(System::translate("Main page"));
	}
	
	public function renderFeed($all = FALSE) {
		if(!Environment::getUser()->isAuthenticated()) {
			$this->forward("default");
		}
		$this->setPageTitle(System::translate("News"));
		$source = Leganto::feed()->getSelector()->findAll();
		$noEvents = false;
		if (!$all) {
			// Get list of all followed users
			$users = Leganto::users()->getSelector()->findAllFollowed(System::user())->fetchPairs("id_user","id_user");
			// User follows no users!
			if(empty($users)) {
				$noEvents = true;
			} else {
				$source->where("id_user IN %l", $users);
			}
		}
		$this->getTemplate()->noEvents = $noEvents;
		$this->getTemplate()->allSwitcher = $all;
		$this->getComponent("feed")->setSource($source);
	}

	public function renderHelp() {
	    $this->setPageTitle(System::translate("Help"));
	}

	public function renderUnauthorized() {
		$this->setPageTitle(System::translate("Unauthorized"));
	}

	protected function createComponentIntroduction($name) {
		return new IntroductionComponent($this,$name);
	}

	protected function createComponentPreview($name) {
		return new PreviewComponent($this,$name);
	}

	protected function createComponentFeed($name) {
	    return new FeedComponent($this, $name);
	}

}
