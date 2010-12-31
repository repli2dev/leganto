<?php
class BookMergerComponent extends BaseComponent
{

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
		}
		if (!Environment::getUser()->isAllowed(Resource::BOOK, Action::EDIT)) {
			$this->getPresenter()->redirect("Default:unauthorized");
		}
		// get books
		$master = Leganto::books()->getSelector()->find($this->state["master"]);
		$slaves = Leganto::books()->fetchAndCreateAll(
			Leganto::books()->getSelector()
				->findAll()
				->where("[id_book_title] IN %l", array_keys($this->state["slave"]))
		);

		// reset state
		unset($this->state["master"]);
		unset($this->state["slave"]);
		$this->persistState();
		// merge books
		foreach($slaves AS $slave) {
			Leganto::books()->getUpdater()->merge($master, $slave);
		}
		// redirect
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
		$this->invalidateControl("bookMerger");
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

	public function setBook(BookEntity $book) {
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
			$source = Leganto::books()->getSelector()->findAll()->where("[id_book_title] IN %l", $books);
			while($book = Leganto::books()->fetchAndCreate($source)) {
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
