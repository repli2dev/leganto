<?php

/**
 * Component for merging duplicite books
 * @author Jan Drabek
 * @author Jan Papousek
 */

namespace FrontModule\Components;

use Nette\Environment,
    Leganto\System,
    Leganto\DB\Factory,
    Leganto\ACL\Resource,
    Leganto\ACL\Action;

class BookMerger extends BaseComponent {

	private $currentBook;
	private $state;

	/**
	 * Add book as slave
	 * @param int $book ID of book
	 */
	public function handleAddSlaveBook($book) {
		if (!isset($this->state["slave"])) {
			$this->state["slave"] = array();
		}
		$this->state["slave"][$book] = TRUE;
		$this->persistState();
		$this->invalidateControl("bookMerger");
	}

	/**
	 * Perform merging of books
	 * @return void
	 */
	public function handleMerge() {
		if (empty($this->state["master"]) || empty($this->state["slave"])) {
			$this->getPresenter()->flashMessage($this->translate('An unexpected error has occurred.'), "error");
			return;
		}
		if (!$this->getUser()->isAllowed(Resource::BOOK, Action::EDIT)) {
			$this->getPresenter()->redirect("Default:unauthorized");
			return;
		}
		// get books
		$master = Factory::book()->getSelector()->find($this->state["master"]);
		$bookIds = array_keys($this->state["slave"]);
		$slaves = Factory::book()->fetchAndCreateAll(
			Factory::book()->getSelector()
				->findAll()
				->where("[id_book_title] IN %l", $bookIds)
		);
		// reset state
		unset($this->state["master"]);
		unset($this->state["slave"]);
		$this->persistState();
		// merge books
		foreach ($slaves AS $slave) {
			Factory::book()->getUpdater()->merge($master, $slave);
		}

		// success
		$this->getService("logger")->log("MERGING SLAVE BOOKS [" . implode(',', $bookIds) . "] WITH MASTER BOOK [" . $master->getId() . "]");
		$this->getPresenter()->flashMessage($this->translate("The books have been merged successfully."), "success");
		$this->getPresenter()->redirect("Book:default", $master->getId());
	}

	/**
	 * Remove slave book from list
	 * @param int $book ID of book
	 */
	public function handleRemoveSlaveBook($book) {
		if (isset($this->state["slave"])) {
			unset($this->state["slave"][$book]);
			$this->persistState();
			$this->invalidateControl("bookMerger");
		}
	}

	/**
	 * Remove master book from list
	 */
	public function handleRemoveMasterBook() {
		unset($this->state["master"]);
		$this->persistState();
		$this->getPresenter()->redirect("this");
	}

	/**
	 * Set master book 
	 * @param int $book ID of book
	 */
	public function handleSetMasterBook($book) {
		$this->state["master"] = $book;
		$this->persistState();
		$this->invalidateControl("bookMerger");
	}

	public function render() {
		if ($this->getUser()->isAllowed(Resource::BOOK, Action::EDIT)) {
			parent::render();
		}
	}

	/**
	 * Set current book
	 * @param \Leganto\DB\Book\Entity $book 
	 */
	public function setBook(\Leganto\DB\Book\Entity $book) {
		$this->currentBook = $book;
	}

	protected function beforeRender() {
		if (empty($this->currentBook)) {
			throw new InvalidStateException("The current book is not set.");
		}
		$this->getTemplate()->book = $this->currentBook;
		$books = array();
		if (!empty($this->state["master"])) {
			$books[] = $this->state["master"];
		}
		if (!empty($this->state["slave"])) {
			$books = array_merge($books, array_keys($this->state["slave"]));
		}
		$this->getTemplate()->slave = array();
		if (!empty($books)) {
			$source = Factory::book()->getSelector()->findAll()->where("[id_book_title] IN %l", $books);
			while ($book = Factory::book()->fetchAndCreate($source)) {
				if (!empty($this->state["master"]) && $book->getId() == $this->state["master"]) {
					$this->getTemplate()->master = $book;
				} else {
					$this->getTemplate()->slave[$book->getId()] = $book;
				}
			}
		}
	}

	protected function startUp() {
		parent::startUp();
		$this->loadPersistedState();
	}

	private function loadPersistedState() {
		$session = $this->getStateSession();
		$this->state = isset($session["state"]) ? $session["state"] : array();
	}

	private function persistState() {
		$session = $this->getStateSession();
		$session["state"] = $this->state;
	}

	private function getStateSession() {
		return $this->getContext()->session->getSection("book-merger");
	}

}
