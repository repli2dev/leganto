<?php

/**
 * Base presenter
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
class BasePresenter extends Presenter {

	protected function createTemplate() {
		$this->oldLayoutMode = false;

		$template = parent::createTemplate();

		return LegantoTemplate::loadTemplate($template);
	}

	protected function startUp() {
		parent::startup();
		$this->oldModuleMode = FALSE;
	}

}
