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
class FeedComponent extends BaseListComponent {

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
		if ($this->firstTime) {
			$this->handleAll();
			$this->flashMessage(System::translate("To provide you the best experience please fill in additional details in") ." ". Html::el("a")->href($this->getPresenter()->link("Settings:default"))->setText(System::translate("Settings")) . ".");
		}
		$this->getTemplate()->allSwitcher = $this->allSwitcher;
		if (empty($this->getTemplate()->allSwitcher)) {
			$users = Leganto::users()->getSelector()->findAllFollowed(System::user())->fetchPairs("id_user", "id_user");
			if (empty($users)) {
				$this->flashMessage(System::translate("Sorry, no events were found. Probably you don't follow any user."), "error");
				$this->handleAll();
				$this->beforeRender();
			} else {
				$this->getSource()->where("id_user IN %l", $users);
			}
		}
		$recommend = Leganto::books()->getSelector()->findRecommendedBook();
		if($recommend !== null) {
			$this->getComponent("bookList")->setSource($recommend);
			$this->getTemplate()->recommend = true;
		} else {
			$this->getTemplate()->recommend = false;
		}
		// Check for new messages
		if(Leganto::messages()->getSelector()->hasNewMessage(System::user()) != FALSE) {
			$this->flashMessage(System::translate("You have a new message. Read it in") ." ". Html::el("a")->href($this->getPresenter()->link("User:messages"))->setText(System::translate("Messaging")) . ".");
		}
		$paginator = $this->getPaginator();
		$paginator->itemCount = $this->getSource()->count();	// FIXME: nicer way?
		$this->getSource()->applyLimit($paginator->itemsPerPage, $paginator->offset);
		$this->getTemplate()->feed = Leganto::feed()->fetchAndCreateAll($this->getSource());
	}

	protected function createComponentFlashMessages($name) {
		return new FlashMessagesComponent($this, $name);
	}

	protected function startUp() {
		$this->setSource(Leganto::feed()->getSelector()->findAll());
	}

	protected function createComponentBookList($name) {
		return new BookListComponent($this, $name);
	}
	

}
