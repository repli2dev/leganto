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
class ShelvesComponent extends BaseComponent {

	/** @var UserEntity */
	private $user;
	private $newOrder;
	private $orderedBook;

	public function handlePersist($shelf) {
		// List sent by AJAX
		$order = $this->getPresenter()->getParam("books");
		// Get shelf entity
		$shelfEntity = Leganto::shelves()->getSelector()->find($shelf);
		// Check permission
		if (!Environment::getUser()->isAllowed(Resource::create($shelfEntity), Action::EDIT)) {
			$this->unauthorized();
		}
		// Books of shelf
		$books = Leganto::books()->fetchAndCreateAll(Leganto::books()->getSelector()->findAllByShelf($shelfEntity));
		try {
			// Update
			Leganto::shelves()->getUpdater()->changeOrder($shelfEntity, $this->getOrderedBook($order, $books), $this->getNewOrder($order, $books));
			System::log("CHANGE ORDER IN SHELF '" . $shelfEntity->getId() . "'");
		} catch (Exception $e) {
			$this->unexpectedError($e);
		}
	}

	public function handleRemove($shelf) {
		$shelfEntity = Leganto::shelves()->getSelector()->find($shelf);
		if (!Environment::getUser()->isAllowed(Resource::create($shelfEntity), Action::EDIT)) {
			$this->unauthorized();
		}
		try {
			$shelfEntity->delete();
			System::log("DELETE SHELF '" . $shelfEntity->getId() . "'");
			$this->getPresenter()->flashMessage(System::translate("The shelf has been successfuly deleted."), "success");
		} catch (Exception $e) {
			$this->unexpectedError($e);
			return;
		}
		$this->getPresenter()->redirect("this");
	}

	public function setUser(UserEntity $user) {
		$this->user = $user;
	}

	public function handleRemoveFromShelf($book, $shelf) {
		// FIXME: lepsi zpusob, nekam to osamostatnit
		$component = new BookShelfControlComponent($this,"bookShelfControl");
		$component->handleRemoveFromShelf($book,$shelf);
		unset($component);
	}

	protected function beforeRender() {
		// Check whether the user is set
		if ($this->user == null) {
			throw new InvalidStateException("The component [$name] can not be rendered, because the user is not set.");
		}
		// Set if this shelves are currently logged user
		if(System::user() !== null && $this->user->getId() === System::user()->getId()) {
			$this->getTemplate()->own = true;
		} else {
			$this->getTemplate()->own = false;
		}
		// Shelves
		$this->getTemplate()->shelves = Leganto::shelves()->fetchAndCreateAll(Leganto::shelves()->getSelector()->findByUser($this->user));
		// Books in shelves
		// FIXME: The books should be represented as entities
		$this->getTemplate()->books = Leganto::books()->getSelector()->findAllInShelvesByUser($this->user)->fetchAssoc("id_shelf,id_book_title");
	}

	private function computeNewOrder(array $order, array $books) {
		if (count($order) != count($books)) {
			throw new InvalidArgumentException("The list of books has different length from list of order.");
		}
		// Get ordered parts of lists 'origin' and 'new' which are different from each other
		$origin = array();
		$new = array();
		$booksById = array();
		$diffStart = -1;

		for ($i = 0; $i < count($order); $i++) {
			$book = $books[$i];
			$orderId = $order[$i];
			if ($book->getId() != $orderId) {
				$origin[] = $book->getId();
				$new[] = $orderId;
				if ($diffStart == -1) {
					$diffStart = $i;
				}
			}
			$booksById[$book->getId()] = $book;
		}
		$diffEnd = $diffStart + count($origin) - 1;

		$firstDiffNew = ExtraArray::firstValue($new);
		$lastDiffNew = ExtraArray::lastValue($new);
		$firstDiffOrigin = ExtraArray::firstValue($origin);
		$lastDiffOrigin = ExtraArray::lastValue($origin);

		if ($firstDiffNew == $lastDiffOrigin) {
			$this->orderedBook = $booksById[$firstDiffNew];
			$this->newOrder = $diffStart;
		} else {
			$this->orderedBook = $booksById[$lastDiffNew];
			$this->newOrder = $diffEnd;
		}
	}

	private function getNewOrder(array $order, array $books) {
		if (empty($this->newOrder)) {
			$this->computeNewOrder($order, $books);
		}
		return $this->newOrder;
	}

	private function getOrderedBook(array $order, array $books) {
		if (empty($this->orderedBook)) {
			$this->computeNewOrder($order, $books);
		}
		return $this->orderedBook;
	}

}
