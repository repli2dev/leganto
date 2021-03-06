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
class Web_BasePresenter extends BasePresenter {

	protected function beforeRender() {
		// HACK
		$this->getComponent("navigation");
	}

	/**
	 * Set title of page
	 * @param string $content
	 */
	public function setPageTitle($pageTitle) {
		$this->getTemplate()->pageTitle = $pageTitle;
	}

	/**
	 * Set keywords of page
	 * @param string $content
	 */
	public function setPageKeywords($content) {
		$this->getTemplate()->pageKeywords = $content;
	}

	/**
	 * Set description of page
	 * @param string $content
	 */
	public function setPageDescription($content) {
		$this->getTemplate()->pageDescription = $content;
	}

	protected function createComponentFlashMessages($name) {
		return new FlashMessagesComponent($this, $name);
	}

	protected function createComponentNavigation($name) {
		return new NavigationComponent($this, $name);
	}

	protected function createComponentSearch($name) {
		return new SearchComponent($this, $name);
	}

	protected final function unauthorized() {
		$this->redirect("Default:unauthorized");
	}

	protected final function unexpectedError(Exception $e) {
		$this->flashMessage(System::translate('An unexpected error has occurred.'), "error");
		Debug::processException($e);
	}

}