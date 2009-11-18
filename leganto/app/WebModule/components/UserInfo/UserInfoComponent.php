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

class UserInfoComponent extends BaseComponent {
	
	public function render(){
		$this->getTemplate()->user = System::user();
		if(Environment::getUser()->isAuthenticated()){
			parent::render();
		}
	}

	public function handleLogout() {
		Environment::getUser()->signOut(TRUE);
	}
}

