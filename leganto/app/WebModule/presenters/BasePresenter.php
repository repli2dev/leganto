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
class Web_BasePresenter extends BasePresenter {

        protected function beforeRender() {
	    // HACK
	    $this->getComponent("navigation");
	}

	public function setPageTitle($pageTitle){
		$this->getTemplate()->pageTitle = $pageTitle;
	}

	protected function createComponentFlashMessages($name) {
	    return new FlashMessagesComponent($this, $name);
	}

	protected function createComponentNavigation($name) {
		return new NavigationComponent($this,$name);
	}

	protected function createComponentSearch($name) {
		return new SearchComponent($this,$name);
	}

	protected final function unauthorized() {
	    $this->redirect("Default:unauthorized");
	}

	protected final function unexpectedError(Exception $e) {
	    $this->flashMessage(System::translate('Unexpected error happened.'), "error");
	    Debug::processException($e);
	}

}