<?php
/**
 * Component which handle (de)placing books into shelves
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */
namespace FrontModule\Components;
use Leganto\System,
	Nette\Environment,
	Leganto\DB\Factory,
	Leganto\ACL\Resource,
	Leganto\ACL\Action,
	Nette\InvalidStateException,
	FrontModule\Forms\BaseForm,
	Nette\Forms\Form,
	Exception,
	Leganto\ORM\Exceptions\DuplicityException;

class BookShelfControl extends BaseComponent {
	const OPTION_CREATE_NEW_SHELF = -1;

	private $book;

	/**
	 * Handle removing book from shelf
	 * @param \Leganto\DB\Book\Entity $book
	 * @param \Leganto\DB\Shelf\Entity $shelf
	 * @return void
	 */
	public function handleRemoveFromShelf($book, $shelf, $return = FALSE) {
		$user = $this->getUser();
		$shelfEntity = Factory::shelf()->getSelector()->find($shelf);
		// Check permission
		if (!$user->isAllowed(Resource::create($shelfEntity), Action::EDIT)) {
			$this->unauthorized();
		}
		$bookEntity = Factory::book()->getSelector()->find($book);
		if (empty($bookEntity)) {
			$this->getPresenter()->flashMessage($this->translate("The given book doesn't exist.", "error"));
			return;
		}
		try {
			Factory::shelf()->getUpdater()->removeBookFromShelf($shelfEntity, $bookEntity);
			$this->getContext()->getService("logger")->log("REMOVED BOOK '" . $bookEntity->getId() . "' FROM SHELF '" . $shelfEntity->getId() . "'");
			$this->getPresenter()->flashMessage($this->translate("The book has been removed from the shelf."), "success");
		} catch (Exception $e) {
			$this->unexpectedError($e);
			return;
		}
		// Return == TRUE means return to shelf (open the shelf)
		if($return) {
			$this->getPresenter()->redirect("this#books-in-shelf-".$shelf);	
		} else {
			if($this->getContext()->httpRequest->isAjax()) {
				$this->getPresenter()->getComponent("flashMessages")->invalidateControl("flashes");
				$this->invalidateControl("bookShelfControl");
			} else {
				$this->getPresenter()->redirect("this");
			}
		}
	}

	/**
	 * Assign book to the component
	 * @param \Leganto\DB\Book\Entity $book 
	 */
	public function setBook(\Leganto\DB\Book\Entity $book) {
		$this->book = $book;
	}

	public function render() {
		if (!$this->getUser()->isAllowed(Resource::BOOK, Action::INSERT)) {
			return;
		}
		$this->loadTemplate();
		return parent::render();
	}
	
	/**
	 * Handle placing book into shelf
	 * @param Form $form
	 * @return void
	 */
	public function formSubmitted(Form $form) {
		$shelf = $form["shelf"]->getValue();
		if ($shelf == self::OPTION_CREATE_NEW_SHELF) {
			$this->getPresenter()->redirect("User:insertShelf", $this->getUser()->getId(), $this->getPresenter()->link("Book:default", $this->book->getId()));
		} else {
			$logger = $this->getContext()->getService("logger");
			try {
				if (empty($shelf)) {
					Factory::shelf()->getUpdater()->removeFromShelves($this->getUser()->getId(), $this->book);
					$logger->log("REMOVE BOOK '" . $this->book->getId() . "' FROM SHELVES");
					$this->getPresenter()->flashMessage($this->translate('Tho book has been removed from the shelf.'), "success");
				} else {
					$shelfEntity = Factory::shelf()->getSelector()->find($shelf);
					Factory::shelf()->getUpdater()->insertToShelf($shelfEntity, $this->book);
					$logger->log("INSERT BOOK '" . $this->book->getId() . "' INTO SHELF '" . $shelfEntity->getId() . "'");
					$this->getPresenter()->flashMessage($this->translate('The book has been inserted to the shelf.'), "success");
				}
			} catch (DuplicityException $e) {
				$this->getPresenter()->flashMessage($this->translate('The book is already present in selected shelf.'), "info");
			} catch (Exception $e) {
				$this->unexpectedError($e);
				return;
			}
			if($this->getContext()->httpRequest->isAjax() || true) {
				$this->invalidateControl("bookShelfControl");	
				$this->getPresenter()->getComponent("flashMessages")->invalidateControl("flashes");
			} else {
				$this->getPresenter()->redirect("this");
			}
		}
	}

	protected function createComponentForm($name) {
		$user = $this->getUserEntity();
		if (empty($user)) {
			throw new InvalidStateException("The component [$name] in [" . $this->getName() . "] can not be created because no user is authenticated.");
		}
		$form = new BaseForm($this, $name);
		$form->getElementPrototype()->class('ajax');

		// Get user's shelves
		$shelves = Factory::shelf()->getSelector()->findByUser($user)->fetchPairs("id_shelf", "name")
			+ array(self::OPTION_CREATE_NEW_SHELF => "--- " . $this->translate("Create a new shelf") . " ---");
		$form->addSelect("shelf", NULL, $shelves)
			->skipFirst("--- " . $this->translate("Select shelf") . " ---");
		// HACK: on change invoke click() event on hidden button
		$form["shelf"]->getControlPrototype()->onChange = "$('#frmform-submitted').click();";
		$form->addSubmit("submitted","Odeslat")->getControlPrototype()->style("visibility:hidden; height: 0px;");

		// Submit settings
		$form->onSuccess[] = array($this, "formSubmitted");

		return $form;
	}

	protected function loadTemplate() {
		// Check whether the book is set
		if (empty($this->book)) {
			throw new InvalidStateException("The component [$name] can not be rendered, because the book is not set.");
		}
		// Check whether the user is authenticated
		$user = $this->getUserEntity();
		if (empty($user)) {
			throw new InvalidStateException("The component [$name] in [" . $this->getName() . "] can not be rendered because no user is authenticated.");
		}
		// Get shelves by book
		$this->getTemplate()->shelves = Factory::shelf()->fetchAndCreateAll(Factory::shelf()->getSelector()->findByUserAndBook($user, $this->book));
		// Get the book
		$this->getTemplate()->book = $this->book;
	}

}
