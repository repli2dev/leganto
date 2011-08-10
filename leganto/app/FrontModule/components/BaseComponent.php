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
	Nette\InvalidStateException;

abstract class BaseComponent extends Control {

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
		$this->getPresenter()->redirect("Default:unauthorized");
	}

	protected final function unexpectedError(Exception $e, $display = TRUE) {
		$this->getPresenter()->flashMessage(System::translate('An unexpected error has occurred.'), "error");
		Debugger::processException($e, $display);
	}
	
	protected function getContext() {
		if($this->presenter === NULL) {
			throw new InvalidStateException("Not attached to any presenter");
		}
		return $this->presenter->getContext();
	}
	

}

