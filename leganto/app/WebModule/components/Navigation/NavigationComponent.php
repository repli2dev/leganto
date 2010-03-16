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

class NavigationComponent extends BaseComponent
{
	public function render() {
		parent::render();
	}

	public function handleLogout() {
		Environment::getUser()->signOut(TRUE);
		// Remove session information belonging to connections (twitter, facebook...)
		$twitter = Environment::getSession("twitter");
		if(!empty($twitter)) {
			$twitter->remove();
		}
		$this->getPresenter()->redirect("Default:");
	}
	
	protected function init() {
	    $this->getTemplate()->user = System::user();
	}
}

