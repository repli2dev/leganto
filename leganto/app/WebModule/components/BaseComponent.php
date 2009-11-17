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

    public function render(){
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
}
