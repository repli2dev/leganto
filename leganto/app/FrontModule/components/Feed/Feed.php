<?php

/**
 * Feed component
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */
namespace FrontModule\Components;
use Leganto\DB\Factory,
	FrontModule\Components\BookList,
	FrontModule\Components\FlashMessages,
	FrontModule\Components\Twitter,
	Leganto\System;
class Feed extends BaseListComponent {

	/** @persistent */
	public $allSwitcher;
	public $firstTime;

	public function handleAll() {
		$this->allSwitcher = TRUE;
	}

	public function handleFollowed() {
		$this->allSwitcher = FALSE;
	}

	protected function beforeRender() {
		parent::beforeRender();
		// First time?
		if ($this->firstTime) {
			$this->handleAll();
			$this->flashMessage(System::translate("To provide you the best experience please fill in additional details in") ." ". Html::el("a")->href($this->getPresenter()->link("Settings:default"))->setText(System::translate("Settings")) . ".");
		}
		// All or followed user?
		$this->getTemplate()->allSwitcher = $this->allSwitcher;
		if (empty($this->getTemplate()->allSwitcher)) {
			$users = Factory::user()->getSelector()->findAllFollowed(System::user())->fetchPairs("id_user", "id_user");
			if (empty($users)) {
				$this->flashMessage(System::translate("Sorry, no events were found. Probably you don't follow any user."), "error");
				$this->handleAll();
				$this->beforeRender();
			} else {
				$this->getSource()->where("id_user IN %l", $users);
			}
		}
		// Set a limit
		$paginator = $this->getPaginator();
		if ($this->getLimit() == 0) {
			$paginator->itemsPerPage = $paginator->itemCount;
		}
		$feed = $this->getSource()->applyLimit($paginator->itemsPerPage, $paginator->offset);
		$this->getTemplate()->feed = Factory::feed()->fetchAndCreateAll($feed);
		// Recommended books
		$recommend = Factory::book()->getSelector()->findRecommendedBook();
		if($recommend !== null) {
			$this->getComponent("bookList")->setSource($recommend);
			$this->getTemplate()->recommend = true;
		} else {
			$this->getTemplate()->recommend = false;
		}
		// Check for new messages
		if(Factory::message()->getSelector()->hasNewMessage(System::user()) != FALSE) {
			$this->flashMessage(System::translate("You have a new message. Read it in") ." ". Html::el("a")->href($this->getPresenter()->link("User:messages"))->setText(System::translate("Messaging")) . ".");
		}
		// Find random "did you know"
		$this->getTemplate()->hint = Factory::help()->getSelector()->findRandom(System::language());
	}

	protected function createComponentFlashMessages($name) {
		return new FlashMessages($this, $name);
	}

	protected function createComponentTwitter($name) {
		return new TwitterComponent($this, $name);
	}

	protected function startUp() {
		$this->setSource(Factory::feed()->getSelector()->findAll());
	}

	protected function createComponentBookList($name) {
		return new BookList($this, $name);
	}
	

}
