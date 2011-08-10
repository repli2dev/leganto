<?php
namespace FrontModule\Components;
use Nette\Environment,
	Leganto\System,
	Leganto\DB\Factory,
	Leganto\ACL\Resource,
	Leganto\ACL\Action;
class BookMerger extends BaseComponent {

	private $currentBook;

	private $state;

	public function handleAddSlaveBook($book) {
		if (!isset($this->state["slave"])) {
			$this->state["slave"] = array();
		}
		$this->state["slave"][$book] = TRUE;
		$this->persistState();
		$this->invalidateControl("bookMerger");
	}

	public function handleMerge() {
		if (empty($this->state["master"]) || empty($this->state["slave"])) {
			$this->getPresenter()->flashMessage(System::translate('An unexpected error has occurred.'), "error");
			return;
		}
		if (!Environment::getUser()->isAllowed(Resource::BOOK, Action::EDIT)) {
			$this->getPresenter()->redirect("Default:unauthorized");
			return;
		}
		// get books
		$master		= Factory::book()->getSelector()->find($this->state["master"]);
		$bookIds	= array_keys($this->state["slave"]);
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
		foreach($slaves AS $slave) {
			Factory::book()->getUpdater()->merge($master, $slave);
		}

		// success
		System::log("MERGING SLAVE BOOKS [" . implode(',', $bookIds) . "] WITH MASTER BOOK [" . $master->getId() . "]");
		$this->getPresenter()->flashMessage(System::translate("The books have been merged successfully."), "success");
		$this->getPresenter()->redirect("Book:default", $master->getId());
	}

	public function handleRemoveSlaveBook($book) {
		if (isset($this->state["slave"])) {
			unset($this->state["slave"][$book]);
			$this->persistState();
			$this->invalidateControl("bookMerger");
		}
	}

	public function handleRemoveMasterBook() {
		unset($this->state["master"]);
		$this->persistState();
		$this->getPresenter()->redirect("this");
	}

	public function handleSetMasterBook($book) {
		$this->state["master"] = $book;
		$this->persistState();
		$this->invalidateControl("bookMerger");
	}

	public function render() {
		if (Environment::getUser()->isAllowed(Resource::BOOK, Action::EDIT)) {
			parent::render();
		}
	}

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
			while($book = Factory::book()->fetchAndCreate($source)) {
				if (!empty($this->state["master"]) && $book->getId() == $this->state["master"]) {
					$this->getTemplate()->master	= $book;
				}
				else {
					$this->getTemplate()->slave[$book->getId()]	= $book;
				}
			}
		}
	}

	protected function startUp() {
		parent::startUp();
		$this->loadPersistedState();
	}

	private function loadPersistedState() {
		$session = Environment::getSession("book-merger");
		$this->state = isset($session["state"]) ? $session["state"] : array();
	}

	private function persistState() {
		$session = Environment::getSession("book-merger");
		$session["state"] = $this->state;
	}

}
