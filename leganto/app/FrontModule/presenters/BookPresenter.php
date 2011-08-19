<?php

/**
 * Book presenter
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */

namespace FrontModule;

use Leganto\DB\Factory,
    Nette\Environment,
    FrontModule\Components\OpinionList,
    FrontModule\Components\EditionList,
    FrontModule\Components\BookMerger,
    FrontModule\Components\RelatedBookList,
    FrontModule\Components\BookView,
    FrontModule\Components\Submenu,
    FrontModule\Components\BookShelfControl,
    FrontModule\Components\ShareBox,
    FrontModule\Components\BookList,
    FrontModule\Components\Edition,
    FrontModule\Components\TagList,
    FrontModule\Components\FollowedUser,
    FrontModule\Components\InsertingOpinion,
    FrontModule\Components\InsertingBook,
    Leganto\ACL\Resource,
    Leganto\ACL\Action,
    Leganto\ACL\Role;

class BookPresenter extends BasePresenter {

	private $book;

	public function renderDefault($book, $edition = NULL) {
		// Opinions
		$opinions = Factory::opinion()->getSelector()
			->findAllByBook($this->getBook(), $this->getService("environment")->domain()->idLanguage)
			->where("[content] != ''")
			->applyLimit(5);
		$this->getComponent("opinionList")->setLimit(5);
		$this->getComponent("opinionList")->showPaginator(FALSE);
		$this->getComponent("opinionList")->setSource($opinions);
		$this->getTemplate()->opinionCount = $opinions->count();
		$this->getTemplate()->bookId = $this->getBook()->getId();
		// Editions
		$this->getComponent("editionList")->setSource(
			Factory::edition()->getSelector()->findAllByBook($this->getBook())
		);
		// Related books
		$this->getComponent("relatedBookList")->setSource(
			Factory::book()->getSelector()->findAllRelated($this->getBook())
		);
		// Edition?
		$this->getComponent("bookView")->setEditionId($edition);
		// Page title
		$this->setPageTitle($this->translate("General info") . ": " . $this->getBook()->title);
		$this->setPageDescription($this->translate("This is the detail of the book where you can find the most interesting data such as the book cover, tags, opinions, editions, related books etc."));
		$this->setPageKeywords($this->translate("book, detail, graphs, opinions, tags, editions, isbn, pages, shelves, share to social network"));
	}

	public function actionAddEdition($book) {
		if (!$this->getUser()->isAllowed(Resource::EDITION, Action::INSERT)) {
			$this->unauthorized();
		} else {
			$this->getTemplate()->book = $this->getBook();
			$this->setPageTitle($this->translate("Add edition") . ": " . $this->getBook()->title);
			$this->setPageDescription($this->translate("You can add a new edition of this book on this page."));
			$this->setPageKeywords($this->translate("add, edition, insert"));
		}
	}

	public function actionEditEdition($book, $edition) {
		if (!$this->getUser()->isAllowed(Resource::EDITION, Action::EDIT)) {
			$this->unauthorized();
		} else {
			$this->setPageTitle($this->translate("Edit edition") . ": " . $this->getBook()->title);
			$this->setPageDescription($this->translate("You can edit an already inserted edition on this page."));
			$this->setPageKeywords($this->translate("edit, update, edition"));
		}
	}

	public function actionAddOpinion($book) {
		if (!$this->getUser()->isAllowed(Resource::OPINION, Action::INSERT)) {
			$this->unauthorized();
		} else {
			$this->getTemplate()->book = $this->getBook();
			$this->setPageTitle($this->translate("Your opinion") . ": " . $this->getBook()->title);
			$this->setPageDescription($this->translate("You can insert or change your opinion on a certain book on this page."));
			$this->setPageKeywords($this->translate("opinion, insert, add, book, your opinion"));
		}
	}

	public function actionRandom() {
		$this->redirect("default", Factory::book()->getSelector()->findRandom()->id_book_title);
	}

	public function renderEdit($book) {
		// Edit book
		$this->getTemplate()->book = $this->getBook();
		if (!$this->getUser()->isAllowed(Resource::BOOK, Action::EDIT)) {
			$this->unauthorized();
		}
		$this->getComponent("insertingBook")->setBookToEdit($this->getBook());
		$this->setPageTitle($this->translate("Edit the book") . " '" . $this->getBook()->title . "'");
	}

	public function renderInsert($skip = FALSE, $book, $related = FALSE) {
		if($skip) {
			$this->getComponent("insertingBook")->handleContinueToInsert("");
		}
		if ($related) {
			if (!$this->getUser()->isAllowed(Resource::BOOK, Action::INSERT)) {
				$this->unauthorized();
			}
			// Insert related book
			$this->getComponent("insertingBook")->setRelatedBook($this->getBook());
			$this->setPageTitle($this->translate("Insert book"));
		} else {
			if (!$this->getUser()->isAllowed(Resource::BOOK, Action::INSERT)) {
				$this->unauthorized();
			}
			// Insert a new book
			$this->setPageTitle($this->translate("Insert book"));
		}

		$this->setPageDescription($this->translate("You can insert a new book on this page. To add an opinion to an already inserted book, please use 'Add opinion'."));
		$this->setPageKeywords($this->translate("insert, add, new book"));
	}

	public function renderOpinions($book) {
		$this->getTemplate()->book = $this->getBook();
		$opinions = Factory::opinion()->getSelector()
			->findAllByBook($this->getTemplate()->book, $this->getService("environment")->domain()->idLanguage, $this->getUserEntity())
			->where("[content] IS NOT NULL AND LENGTH(TRIM([content])) > 0");
		$this->getComponent("opinionList")->setSource($opinions);
		$this->getTemplate()->opinionCount = $opinions->count();
		$this->setPageTitle($this->translate("Opinions") . ": " . $this->getTemplate()->book->title);
		$this->setPageDescription($this->translate("Opinions on a certain book from all users, decide if it is worth reading!"));
		$this->setPageKeywords($this->translate("opinion, other users, how to decide what to read"));
	}

	public function renderSimilar($book) {
		$this->getTemplate()->book = $this->getBook();
		$this->getComponent("similarBooks")->setLimit(0);
		$this->getComponent("similarBooks")->setSource(
			Factory::book()->getSelector()->findAllSimilar($this->getTemplate()->book)->applyLimit(12)
		);
		$this->setPageTitle($this->translate("Similar books") . ": " . $this->getTemplate()->book->title);
		$this->setPageDescription($this->translate("Similar books to a certain book, generated according to book tags. Choose what to read according to what you have read!"));
		$this->setPageKeywords($this->translate("similar books, tags, how to choose book"));
	}

	protected function createComponentBookMerger($name) {
		$merger = new BookMerger($this, $name);
		$merger->setBook($this->getBook());
		return $merger;
	}

	protected function createComponentBookView($name) {
		$view = new BookView($this, $name);
		$view->setBook($this->getBook());
		return $view;
	}

	protected function createComponentBookShelfControl($name) {
		$component = new BookShelfControl($this, $name);
		$component->setBook($this->getBook());
		return $component;
	}

	protected function createComponentInsertingBook($name) {
		return new InsertingBook($this, $name);
	}

	protected function createComponentEditionForm($name) {
		return new Edition($this, $name);
	}

	protected function createComponentEditionList($name) {
		return new EditionList($this, $name);
	}

	protected function createComponentInsertingOpinion($name) {
		return new InsertingOpinion($this, $name);
	}

	protected function createComponentOpinionList($name) {
		$list = new OpinionList($this, $name);
		if ($this->getAction() == "opinions") {
			$list->showSorting();
		}
		return $list;
	}

	protected function createComponentRelatedBookList($name) {
		return new RelatedBookList($this, $name);
	}

	protected function createComponentSimilarBooks($name) {
		return new BookList($this, $name);
	}

	protected function createComponentShareBox($name) {
		return new ShareBox($this, $name);
	}

	protected function createComponentFollowedUser($name) {
		return new FollowedUser($this, $name);
	}

	protected function createComponentSubmenu($name) {
		$submenu = new Submenu($this, $name);
		$submenu->addLink("default", $this->translate("General info"), $this->getBook()->getId(), $this->translate("Show authors, editions, graphs and few opinions."));
		$submenu->addLink("opinions", $this->translate("Opinions"), $this->getBook()->getId(), $this->translate("What other users say about this book"));
		$submenu->addLink("similar", $this->translate("Similar books"), $this->getBook()->getId(), $this->translate("Similar books according tags"));
		$submenu->addLink("Search:allBooks", $this->translate("All books"));
		$submenu->addLink("random", $this->translate("Random book"), NULL, $this->translate("Bored? Click to get random book."));
		if ($this->getUser() != NULL) {
			$opinion = Factory::opinion()->getSelector()->findByBookAndUser($this->getBook(), $this->getUser());
		}
		if (empty($opinion) && $this->getUser()->isAllowed(Resource::OPINION, Action::INSERT)) {
			$submenu->addEvent("addOpinion", $this->translate("Add opinion"), $this->getBook()->getId());
		} else if (!empty($opinion) && Environment::getUser()->isAllowed(Resource::create($opinion), Action::EDIT)) {
			$submenu->addEvent("addOpinion", $this->translate("Change opinion"), $this->getBook()->getId());
		}
		if ($this->getUser()->isAllowed(Resource::BOOK, Action::EDIT)) {
			$submenu->addEvent("edit", $this->translate("Edit book"), $this->getBook()->getId());
		}
		if ($this->getUser()->isAllowed(Resource::BOOK, Action::INSERT)) {
			$submenu->addevent("insert", $this->translate("Insert related book"), array("book" => $this->getBook()->getId(), "related" => TRUE));
		}
		if ($this->getUser()->isAllowed(Resource::EDITION, Action::INSERT)) {
			$submenu->addEvent("addEdition", $this->translate("Add new edition"), $this->getBook()->getId());
		}
		$edition = $this->getComponent("bookView")->getEditionId();
		if ($this->getUser()->isAllowed(Resource::EDITION, Action::EDIT) && !empty($edition)) {
			$submenu->addEvent("editEdition", $this->translate("Edit this edition"), array("book" => $this->getBook()->getId(), "edition" => $edition));
		}

		return $submenu;
	}

	protected function createComponentTagList($name) {
		$tags = new TagList($this, $name);
		$tags->setBook($this->getBook());
		$tags->setSource(Factory::tag()->getSelector()->findAllByBook($this->getBook(), $this->getService("environment")->domain()->idLanguage));
		return $tags;
	}

	public function renderSuggest($term) {
		$cache = Environment::getCache("bookSuggest");
		if (isSet($cache[md5($term)])) {
			echo json_encode($cache[md5($term)]);
		} else {
			$results = array();
			$items = Factory::book()->getSelector()->suggest($term)->select("title")->applyLimit(10)->fetchAssoc("title");
			foreach ($items as $item) {
				$results[] = $item->title;
			}
			echo json_encode($results);
			$cache->save(md5($term), $results, array(
			    'expire' => time() + 60 * 60 * 6, // expire in 6 hours
			));
		}
		die; // Fast respond needed, not want to overload server
	}

	public function renderTagSuggest($term) {
		$cache = Environment::getCache("tagSuggest");
		if (isSet($cache[md5($term)])) {
			echo json_encode($cache[md5($term)]);
		} else {
			$items = Factory::tag()->getSelector()->suggest($term)->select("name")->applyLimit(10)->fetchAssoc("name");
			foreach ($items as $item) {
				$results[] = $item->name;
			}
			echo json_encode($results);
			$cache->save(md5($term), $results, array(
			    'expire' => time() + 60 * 60 * 6, // expire in 6 hours
			));
		}
		die; // Fast respond needed, not want to overload server
	}

	// PRIVATE METHODS

	/** @return BookEntity */
	private function getBook() {
		if (empty($this->book)) {
			$this->book = $this->getTemplate()->book = Factory::book()->getSelector()->find($this->getParam("book"));
		}
		return $this->book;
	}

}