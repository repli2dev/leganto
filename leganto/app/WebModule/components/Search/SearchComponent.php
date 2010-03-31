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

class SearchComponent extends BaseComponent
{

    public function render() {
	$this->getTemplate()->form = $this->getComponent("form");
	parent::render();
    }

    protected function createComponentForm($name) {
	$form = new AppForm($this, $name);
	$form->getElementPrototype()->setId("search");
	$form->addText("query");
	$form->addSubmit("search_submit", "");
    }

}

