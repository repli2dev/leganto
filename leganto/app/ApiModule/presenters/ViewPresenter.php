<?php

/**
 * Readonly API
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://git.yavanna.cz/?p=leganto.git
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace ApiModule;

use Leganto\DB\Factory,
    Leganto\ORM\DataNotFoundException,
    DibiDriverException,
    Nette\Diagnostics\Debugger,
    Exception,
    InvalidArgumentException;

class ViewPresenter extends BasePresenter {

	public function renderAuthor($id) {
		if (empty($id)) {
			$this->code(404, "Author's ID expected.");
		}
		try {
			$author = Factory::author()->getSelector()->find($id);
			if (empty($author)) {
				$this->code(404);
			}

			$rows = Factory::book()->getSelector()->findAllByAuthor($author);
			$this->getTemplate()->books = array();
			while ($book = Factory::book()->fetchAndCreate($rows)) {
				$this->getTemplate()->books[$book->bookNode][] = $book;
			}

			$this->getTemplate()->author = $author;
		} catch (DataNotFoundException $e) {
			Debugger::processException($e);
			$this->code(404, "The author does not exist.");
		} catch (DibiDriverException $e) {
			Debugger::processException($e);
			$this->code(500, "Database error.");
		}
	}

	public function renderAvailable() {
		
	}

	public function renderBook($id) {
		if (empty($id)) {
			$this->code(404, "Book ID expected.");
		}
		try {
			$book = Factory::book()->getSelector()->find($id);
			if ($book === NULL) {
				$this->code(404, "The book does not exist.");
			}
			$this->getTemplate()->book = $book;

			// Other titles
			$rows = Factory::book()->getSelector()->findOthers($book);
			$this->getTemplate()->other = array();
			while ($entity = Factory::book()->fetchAndCreate($rows)) {
				$this->getTemplate()->other[] = $entity;
			}

			// Authors
			$rows = Factory::author()->getSelector()->findAllByBook($book);
			$this->getTemplate()->authors = array();
			while ($entity = Factory::author()->fetchAndCreate($rows)) {
				$this->getTemplate()->authors[] = $entity;
			}

			// Tags
			$rows = Factory::tag()->getSelector()->findAllByBook($book);
			$this->getTemplate()->tags = array();
			while ($entity = Factory::tag()->fetchAndCreate($rows)) {
				$this->getTemplate()->tags[] = $entity;
			}
		} catch (DataNotFoundException $e) {
			Debugger::processException($e);
			$this->code(404);
		} catch (DibiDriverException $e) {
			Debugger::processException($e);
			$this->code(500, "Database error.");
		}
	}

	public function renderDiscussions($type = NULL, $offset = 0, $limit = 10) {
		if ($limit > 100) {
			$limit = 100;
		}
		try {
			$rows = Factory::discussion()->getSelector()->findAll();
			if (!empty($type)) {
				$rows->where("[id_discussable] = %i", $type);
			}
			$this->getTemplate()->discussions = Factory::discussion()->fetchAndCreateAll($rows->applyLimit($limit, $offset));
		} catch (DibiDriverException $e) {
			Debugger::processException($e);
			$this->code(500, "Database error.");
		}
	}

	public function renderUserOpinions($user, $empty = TRUE, $offset = 0, $limit = 10) {
		if (empty($user)) {
			$this->code(404);
		}
		if ($limit > 100) {
			$limit = 100;
		}
		try {
			// Fetch user entity
			$userEntity = Factory::user()->getSelector()->find($user);
			if ($userEntity == NULL) {
				$this->code(404);
			}
			$this->getTemplate()->user = $userEntity;
			// Book
			$rows = Factory::opinion()->getSelector()->findAllByUser($userEntity, $empty)->applyLimit($limit, $offset);
			$this->getTemplate()->opinions = array();
			while ($opinion = Factory::opinion()->fetchAndCreate($rows)) {
				$this->getTemplate()->opinions[] = $opinion;
			}
			if ($this->getTemplate()->opinions === NULL) {
				$this->code(404);
			}
		} catch (DibiDriverException $e) {
			Debugger::processException($e);
			$this->code(500, "Database error.");
		}
	}

	public function renderBookOpinions($book, $user = NULL, $offset = 0, $limit = 10) {
		if (empty($book)) {
			$this->code(404);
		}
		if ($limit > 100) {
			$limit = 100;
		}
		try {
			// Book
			$this->getTemplate()->book = Factory::book()->getSelector()->find($book);
			if ($this->getTemplate()->book === NULL) {
				$this->code(404);
			}

			// Authors
			$rows = Factory::author()->getSelector()->findAllByBook($this->getTemplate()->book);
			$this->getTemplate()->authors = array();
			while ($entity = Factory::author()->fetchAndCreate($rows)) {
				$this->getTemplate()->authors[] = $entity;
			}

			// Opinions
			if (empty($user)) {
				$rows = Factory::opinion()->getSelector()->findAllByBook($this->getTemplate()->book, $this->getService("environment")->domain()->idLanguage)->applyLimit($limit, $offset);
			} else {
				$userEntity = Factory::user()->getSelector()->find($user);
				if ($userEntity == NULL) {
					$this->code(404);
				}
				$rows = Factory::opinion()->getSelector()->findAllByBook($this->getTemplate()->book, $this->getService("environment")->domain()->idLanguage, $userEntity)->applyLimit($limit, $offset);
			}
			$this->getTemplate()->opinions = array();
			while ($opinion = Factory::opinion()->fetchAndCreate($rows)) {
				$this->getTemplate()->opinions[] = $opinion;
			}
		} catch (DibiDriverException $e) {
			Debugger::processException($e);
			$this->code(500, "Database error.");
		}
	}

	public function renderPosts($id, $type, $offset = 0, $limit = 10) {
		if ($limit > 100) {
			$limit = 100;
		}
		try {
			$rows = Factory::post()->getSelector()->findAllByIdAndType($id, $type);
			$this->getTemplate()->posts = array();
			while ($post = Factory::post()->fetchAndCreate($rows)) {
				$this->getTemplate()->posts[] = $post;
			}
		} catch (InvalidArgumentException $e) {
			Debugger::processException($e);
			$this->code(404);
		} catch (DibiDriverException $e) {
			Debugger::processException($e);
			$this->code(500, "Database error.");
		}
	}

	public function renderShelf($id, $offset = 0, $limit = 10) {
		if (empty($id)) {
			$this->code(404);
		}
		if ($limit > 100) {
			$limit = 100;
		}
		try {
			// Shelf
			$this->getTemplate()->shelf = Factory::shelf()->getSelector()->find($id);
			if ($this->getTemplate()->shelf === NULL) {
				$this->code(404);
			}

			// Books
			$rows = Factory::book()->getSelector()->findAllByShelf($this->getTemplate()->shelf)->applyLimit($limit, $offset);
			$this->getTemplate()->books = Factory::book()->fetchAndCreateAll($rows);
		} catch (DibiDriverException $e) {
			Debugger::processException($e);
			$this->code(500, "Database error.");
		}
	}

	public function renderSimilarBooks($book, $offset = 0, $limit = 10) {
		if (empty($book)) {
			$this->code(404);
		}
		if ($limit > 100) {
			$limit = 100;
		}
		try {
			// Book
			$this->getTemplate()->book = Factory::book()->getSelector()->find($book);
			if ($this->getTemplate()->book === NULL) {
				$this->code(404);
			}

			// Similar books
			$rows = Factory::book()->getSelector()->findAllSimilar($this->getTemplate()->book)->applyLimit($limit, $offset);
			$this->getTemplate()->similar = Factory::book()->fetchAndCreateAll($rows);
		} catch (DibiDriverException $e) {
			Debugger::processException($e);
			$this->code(500, "Database error.");
		}
	}

	public function renderSearchUsers($query, $offset = 0, $limit = 10) {
		if (empty($query)) {
			$this->code(404);
		}
		if ($limit > 100) {
			$limit = 100;
		}
		try {
			$rows = Factory::user()->getSelector()->search($query)->applyLimit($limit, $offset);
			$this->getTemplate()->users = Factory::user()->fetchAndCreateAll($rows);
		} catch (DibiDriverException $e) {
			Debugger::processException($e);
			$this->code(500, "Database error.");
		}
	}

	public function renderSimilarUsers($user, $offset = 0, $limit = 10) {
		if (empty($user)) {
			throw new Exception();
			$this->code(404);
		}
		if ($limit > 100) {
			$limit = 100;
		}
		try {
			$this->getTemplate()->user = Factory::user()->getSelector()->find($user);
			if ($user == NULL) {
				throw new Exception();
				$this->code(404);
			}
			$rows = Factory::user()->getSelector()->findAllSimilar($this->getTemplate()->user)->applyLimit($limit, $offset);
			$this->getTemplate()->users = Factory::user()->fetchAndCreateAll($rows);
		} catch (DibiDriverException $e) {
			Debugger::processException($e);
			$this->code(500, "Database error.");
		}
	}

	public function renderUser($id) {
		if (empty($id)) {
			$this->code(404);
		}
		try {
			// User's info
			$user = Factory::user()->getSelector()->find($id);
			if ($user == NULL) {
				$this->code(404);
			}
			$this->getTemplate()->user = $user;
			// User's shelfs
			$rows = Factory::shelf()->getSelector()->findByUser($user);
			$this->getTemplate()->shelves = array();
			while ($entity = Factory::shelf()->fetchAndCreate($rows)) {
				$this->getTemplate()->shelves[] = $entity;
			}
		} catch (DataNotFoundException $e) {
			Debugger::processException($e);
			$this->code(404);
		} catch (DibiDriverException $e) {
			Debugger::processException($e);
			$this->code(500, "Database error.");
		}
	}

	public function renderSearchBooks($query, $offset = 0, $limit = 10) {
		if (empty($query)) {
			$this->code(404);
		}
		try {
			// Book search
			$rows = Factory::book()->getSelector()->search($query)->applyLimit($limit, $offset);
			$books = $rows->fetchAssoc("id_book_title");
			$authors = $rows->where("[id_book_title] IN %l", array_keys($books))->fetchAssoc("id_book_title,id_author");
			$this->getTemplate()->books = array();
			foreach ($books as $book) {
				$entity = Factory::book()->createEmpty()->loadDataFromArray($book->toArray());
				$this->getTemplate()->books[] = $entity;
			}

			$this->getTemplate()->authors = array();
			foreach ($authors as $bookTitleId => $authorGroup) {
				$this->getTemplate()->authors[$bookTitleId] = array();
				foreach ($authorGroup AS $author) {
					$entity = Factory::author()->createEmpty()->loadDataFromArray($author->toArray());
					$this->getTemplate()->authors[$bookTitleId][] = $entity;
				}
			}
		} catch (DataNotFoundException $e) {
			throw new Exception;
			Debugger::processException($e);
			$this->code(404);
		} catch (DibiDriverException $e) {
			Debugger::processException($e);
			$this->code(500, "Database error.");
		}
	}

	/**
	 * Dummy page for "login", although it is possible on all pages
	 * Show identity
	 */
	public function actionLogin() {
		$this->getTemplate()->identity = $this->getUser()->getIdentity();
	}

	public function actionLogout() {
		$this->getUser()->signOut(TRUE);
	}

}