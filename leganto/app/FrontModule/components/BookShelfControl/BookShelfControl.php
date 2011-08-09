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
namespace FrontModule\Components;
use Leganto\System,
	Nette\Environment,
	Leganto\DB\Factory,
	Leganto\ACL\Resource,
	Leganto\ACL\Action,
	Nette\InvalidStateException;
class BookShelfControl extends BaseComponent {
	const OPTION_CREATE_NEW_SHELF = -1;

	private $book;

	public function handleRemoveFromShelf($book, $shelf) {
		$user = System::user();
		$shelfEntity = Factory::shelves()->getSelector()->find($shelf);
		// Check permission
		if (!Environment::getUser()->isAllowed(Resource::create($shelfEntity), Action::EDIT)) {
			$this->unathorized();
		}
		$bookEntity = Factory::books()->getSelector()->find($book);
		if (empty($bookEntity)) {
			$this->getPresenter()->flashMessage(System::translate("The given book doesn't exist.", "error"));
			return;
		}
		try {
			Factory::shelves()->getUpdater()->removeBookFromShelf($shelfEntity, $bookEntity);
			System::log("REMOVED BOOK '" . $bookEntity->getId() . "' FROM SHELF '" . $shelfEntity->getId() . "'");
			$this->getPresenter()->flashMessage(System::translate("The book has been removed from the shelf."), "success");
		} catch (Exception $e) {
			$this->unexpectedError($e);
			return;
		}
                // FIXME: lepsi rozliseni navratove stranky (viz FIXME v shelvesComponent)
		$this->getPresenter()->redirect("this#books-in-shelf-".$shelf);
	}

	public function setBook(\Leganto\DB\Book\Entity $book) {
		$this->book = $book;
	}

	public function render() {
		if (!Environment::getUser()->isAllowed(Resource::BOOK, Action::INSERT)) {
			return;
		}
		$this->loadTemplate();
		return parent::render();
	}

	public function formSubmitted(Form $form) {
		$shelf = $form["shelf"]->getValue();
		if ($shelf == self::OPTION_CREATE_NEW_SHELF) {
			$this->getPresenter()->redirect("User:insertShelf", System::user()->getId(), $this->getPresenter()->link("Book:default", $this->book->getId()));
		} else {
			try {
				if (empty($shelf)) {
					Factory::shelves()->getUpdater()->removeFromShelves(System::user(), $this->book);
					System::log("REMOVE BOOK '" . $this->book->getId() . "' FROM SHELVES");
					$this->getPresenter()->flashMessage(System::translate('Tho book has been removed from the shelf.'), "success");
				} else {
					$shelfEntity = Factory::shelves()->getSelector()->find($shelf);
					Factory::shelves()->getUpdater()->insertToShelf($shelfEntity, $this->book);
					System::log("INSERT BOOK '" . $this->book->getId() . "' INTO SHELF '" . $shelfEntity->getId() . "'");
					$this->getPresenter()->flashMessage(System::translate('The book has been inserted to the shelf.'), "success");
				}
			} catch (Exception $e) {
				$this->unexpectedError($e);
				return;
			}
			$this->getPresenter()->redirect("this");
		}
	}

	protected function createComponentForm($name) {
		$user = System::user();
		if (empty($user)) {
			throw new InvalidStateException("The component [$name] in [" . $this->getName() . "] can not be created because no user is authenticated.");
		}
		$form = new BaseForm($this, $name);

		// Get user's shelves
		$shelves = Factory::shelves()->getSelector()->findByUser($user)->fetchPairs("id_shelf", "name")
			+ array(self::OPTION_CREATE_NEW_SHELF => "--- " . System::translate("Create a new shelf") . " ---");
		$form->addSelect("shelf", NULL, $shelves)
			->skipFirst("--- " . System::translate("Select shelf") . " ---");
		$form["shelf"]->getControlPrototype()->onChange = "form.submit()";

		// Submit settings
		$form->onSubmit[] = array($this, "formSubmitted");

		return $form;
	}

	protected function loadTemplate() {
		// Check whether the book is set
		if (empty($this->book)) {
			throw new InvalidStateException("The component [$name] can not be rendered, because the book is not set.");
		}
		// Check whether the user is authenticated
		$user = System::user();
		if (empty($user)) {
			throw new InvalidStateException("The component [$name] in [" . $this->getName() . "] can not be rendered because no user is authenticated.");
		}
		// Get shelves by book
		$this->getTemplate()->shelves = Factory::shelves()->fetchAndCreateAll(Factory::shelves()->getSelector()->findByUserAndBook($user, $this->book));
		// Get the book
		$this->getTemplate()->book = $this->book;
	}

}
