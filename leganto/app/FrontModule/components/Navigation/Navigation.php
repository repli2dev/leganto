<?php

/**
 * Main menu component & user  logout
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace FrontModule\Components;

class Navigation extends BaseComponent {

	public function render() {
		parent::render();
	}

	public function handleLogout() {
		$this->getContext()->getService("logger")->log("LOGOUT");
		$this->getUser()->logout(TRUE);
		$this->getPresenter()->redirect("this");
	}

	protected function startUp() {
		parent::startUp();
		$this->getTemplate()->user = $this->getUser();
		if($this->getUser()->isLoggedIn()) {
			$this->getTemplate()->nickname = $this->getUser()->getIdentity()->nickname;
		} else {
			$this->getTemplate()->nickname = "";
		}
	}

}

