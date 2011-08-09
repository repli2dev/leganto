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
use \Leganto\System;

class Navigation extends BaseComponent {

	public function render() {
		parent::render();
	}

	public function handleLogout() {
		System::log("LOGOUT");
		Environment::getUser()->signOut(TRUE);
		$this->getPresenter()->redirect("this");
	}

	protected function startUp() {
		parent::startUp();
		$this->getTemplate()->user = System::user();
	}

}

