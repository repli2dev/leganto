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
namespace FrontModule\Components;
use	Nette\Diagnostics\Debugger,
	Nette\Application\UI\Control,
	Nette\ComponentModel\IContainer,
	Leganto\Templating\Template,
	Nette\Utils\Strings,
	Leganto\Templating\Helpers,
	Nette\InvalidStateException,
	Leganto\DB\Factory,
	Nette\Application\BadRequestException,
	Exception;

abstract class BaseComponent extends Control {
	
	private static $userEntity;

	public function __construct(IContainer $parent = NULL, $name = NULL) {
		parent::__construct($parent, $name);
		$this->startUp();
	}

	public function render() {
		$this->beforeRender();
		$this->getTemplate()->render();
	}

	protected function createTemplate() {
		$template = parent::createTemplate();

		$componentName = Helpers::parseClassName(get_class($this));

		$template->setFile(
			dirname(__FILE__) . "/" .
			$componentName . "/" .
			$componentName . ".latte"
		);

		return Template::loadTemplate($template);
	}

	protected function getPath() {
		$componentName = Helpers::parseClassName(get_class($this));
		return dirname(__FILE__) . "/" . $componentName . "/";
	}

	protected function beforeRender() {
		
	}

	protected function startUp() {
		
	}

	protected final function unauthorized() {
		throw new BadRequestException("",403);
	}

	protected final function unexpectedError(Exception $e, $display = TRUE) {
		$this->getPresenter()->flashMessage($this->translate('An unexpected error has occurred.'), "error");
		Debugger::log($e);
	}
	
	protected function getContext() {
		if($this->presenter === NULL) {
			throw new InvalidStateException("Not attached to any presenter");
		}
		return $this->getPresenter()->getContext();
	}
	
	protected function getUser() {
		if($this->presenter === NULL) {
			throw new InvalidStateException("Not attached to any presenter");
		}
		return $this->getPresenter()->user;
	}
	
	protected function getUserEntity() {
		if(empty(self::$userEntity)) {
			self::$userEntity = Factory::user()->getSelector()->find($this->getUser()->getId());
		}
		return self::$userEntity;
	}

	/** @return bool */
	final public function isCurrentlyAuthenticated() {
		if ($this->getUser() !== null) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/** @return bool */
	final public function isCurrentlyLogged($user) {
		if ($this->getUser() !== null && $user === $this->getUser()->getId()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/** @return bool */
	final public function isCurrentUserAdmin() {
		$user = $this->getUser();
		if ($user !== null && $user->isInRole(\Leganto\DB\User\Entity::ADMIN)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	/** @return string */
	protected final function translate($message, $count = 1) {
		$args = func_get_args();
		return call_user_func_array(array($this->getContext()->getService("translator")->get(), 'translate'), $args);
	}
	

}

