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
	/**
	 * Doporučené knihy
	 * SELECT DISTINCT id_book_title FROM opinion WHERE id_book_title NOT IN (
	 *	SELECT id_book_title FROM opinion WHERE id_user=445) AND id_book_title IN (
	 *		SELECT id_book_title FROM opinion WHERE id_user = (
	 *			SELECT id_user_to FROM user_similarity WHERE id_user_from=445 ORDER BY value DESC LIMIT 1
	 *		)
	 *	)
	 */

}
