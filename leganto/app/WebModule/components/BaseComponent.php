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
 *
 */

abstract class BaseComponent extends Control
{

    public function __construct(/*Nette\*/IComponentContainer $parent = NULL, $name = NULL) {
	parent::__construct($parent, $name);
	$this->startUp();
    }

    public function render(){
	$this->beforeRender();
	$this->getTemplate()->render();

    }

    protected function createTemplate() {
	$template = parent::createTemplate();

	$componentName = strtr($this->getClass(), array("Component" => ""));

	$template->setFile(
	    dirname(__FILE__) . "/" .
	    $componentName . "/" .
	    ExtraString::lowerFirst($componentName) . ".phtml"
	);

	return LegantoTemplate::loadTemplate($template);
    }

    protected function getPath() {
	    $componentName = strtr($this->getClass(), array("Component" => ""));
	    return dirname(__FILE__) . "/" . $componentName . "/";
    }

    protected function beforeRender() {}

    protected function startUp() {}

    protected final function unauthorized() {
		$this->getPresenter()->redirect("Default:unauthorized");
    }

    protected final function unexpectedError(Exception $e) {
		$this->getPresenter()->flashMessage(System::translate('Unexpected error happened.'), "error");
		Debug::processException($e, TRUE);
    }
}

