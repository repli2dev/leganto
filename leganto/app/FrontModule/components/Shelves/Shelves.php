<?php

/**
 * Shelves of user
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace FrontModule\Components;

use Leganto\DB\Factory,
    Leganto\ACL\Resource,
    Leganto\ACL\Action,
    Exception,
    FrontModule\Components\BookShelfControl,
    Nette\InvalidStateException,
    InvalidArgumentException;

class Shelves extends BaseComponent {

	/** @var Entity */
	private $user;
	private $newOrder;
	private $orderedBook;

	public function handlePersist($shelf) {
		// List sent by AJAX
		$order = $this->getPresenter()->getParam("books");
		// Get shelf entity
		$shelfEntity = Factory::shelf()->getSelector()->find($shelf);
		// Check permission
		if (!$this->getUser()->isAllowed(Resource::create($shelfEntity), Action::EDIT)) {
			$this->unauthorized();
		}
		// Books of shelf
		$books = Factory::book()->fetchAndCreateAll(Factory::book()->getSelector()->findAllByShelf($shelfEntity));
		$logger = $this->getContext()->getService("logger");
		try {
			// Update
			Factory::shelf()->getUpdater()->changeOrder($shelfEntity, $this->getOrderedBook($order, $books), $this->getNewOrder($order, $books));
			$logger->log("CHANGE ORDER IN SHELF '" . $shelfEntity->getId() . "'");
		} catch (Exception $e) {
			$this->unexpectedError($e);
		}
	}

	public function handleRemove($shelf) {
		$shelfEntity = Factory::shelf()->getSelector()->find($shelf);
		if (!$this->getUser()->isAllowed(Resource::create($shelfEntity), Action::EDIT)) {
			$this->unauthorized();
		}
		$logger = $this->getContext()->getService("logger");
		try {
			$shelfEntity->delete();
			$logger->log("DELETE SHELF '" . $shelfEntity->getId() . "'");
			$this->getPresenter()->flashMessage($this->translate("The shelf has been successfuly deleted."), "success");
		} catch (Exception $e) {
			$this->unexpectedError($e);
			return;
		}
		$this->getPresenter()->redirect("this");
	}

	public function setUser(\Leganto\DB\User\Entity $user) {
		$this->user = $user;
	}

	public function handleRemoveFromShelf($book, $shelf) {
		// FIXME: lepsi zpusob, nekam to osamostatnit
		$component = new BookShelfControl($this, "bookShelfControl");
		$component->handleRemoveFromShelf($book, $shelf);
		unset($component);
	}

	protected function beforeRender() {
		// Check whether the user is set
		if ($this->user == null) {
			throw new InvalidStateException("The component [$name] can not be rendered, because the user is not set.");
		}
		// Set if this shelves are currently logged user
		$this->getTemplate()->own = $this->isCurrentlyLogged($this->user->getId());
		$this->getTemplate()->user = $this->user;
		// Shelves
		$this->getTemplate()->shelves = Factory::shelf()->fetchAndCreateAll(Factory::shelf()->getSelector()->findByUser($this->user));
		// Books in shelves
		// FIXME: The books should be represented as entities
		$this->getTemplate()->books = Factory::book()->getSelector()->findAllInShelvesByUser($this->user)->fetchAssoc("id_shelf,id_book_title");
		
		// User object (Nette)
		$this->getTemplate()->user2 = $this->getUser();
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
