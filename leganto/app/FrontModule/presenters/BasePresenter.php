<?php

/**
 * Base presenter
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace FrontModule;

use FrontModule\Components\Navigation,
    FrontModule\Components\Search,
    FrontModule\Components\FlashMessages,
    Nette\Diagnostics\Debugger,
    Nette\Application\BadRequestException,
    Leganto\ACL\Role,
    Leganto\DB\Factory,
    Exception;

class BasePresenter extends \BasePresenter {

	private static $userEntity;

	protected function startUp() {
		parent::startUp();

		// Inject currently logged user to Role because of owner assertions
		Role::setUser($this->getUser());
	}

	protected function beforeRender() {
		// HACK
		$this->getComponent("navigation");

		// Sets some variables
		$this->getTemplate()->domain = $this->getService("environment")->domain();
		$this->getTemplate()->robots = true;
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
		return new FlashMessages($this, $name);
	}

	protected function createComponentNavigation($name) {
		return new Navigation($this, $name);
	}

	protected function createComponentSearch($name) {
		return new Search($this, $name);
	}

	protected final function unauthorized() {
		throw new BadRequestException("", 403);
	}

	protected final function unexpectedError(Exception $e) {
		$this->flashMessage($this->translate('An unexpected error has occurred.'), "error");
		Debugger::log($e);
	}

	/** @return string */
	protected final function translate($message, $count = 1) {
		$args = func_get_args();
		return call_user_func_array(array($this->getContext()->getService("translator")->get(), 'translate'), $args);
	}

	protected function getUserEntity() {
		if (empty(self::$userEntity)) {
			self::$userEntity = Factory::user()->getSelector()->find($this->getUser()->getId());
		}
		return self::$userEntity;
	}

}