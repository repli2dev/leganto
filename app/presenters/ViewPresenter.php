<?php
/**
 * @author Jan Papousek
 */
class ViewPresenter extends BasePresenter
{

	/* VIEWS */

	public function renderAuthor($id) {
		if (empty($id)) {
			$this->forward("404");
		}
		try {
			$author = Leganto::authors()->getSelector()->find($id);
			if (empty($author)) {
				$this->forward("404");
			}

			$rows = Leganto::books()->getSelector()->findAllByAuthor($author);
			$this->getTemplate()->books = array();
			while ($book = Leganto::books()->fetchAndCreate($rows)) {
				$this->getTemplate()->books[$book->bookNode][] = $book;
			}
			
			$this->getTemplate()->author = $author;

		}
		catch (DataNotFoundException $e) {
			Debug::processException($e);
			$this->forward("404");
		}
		catch(DibiDriverException $e) {
			Debug::processException($e);
			$this->forward("500");
		}
	}

	public function renderAvaiable() {}

	public function renderBook($id) {
		if (empty($id)) {
			$this->forward("404");
		}
		try {
			$book = Leganto::books()->getSelector()->find($id);
			if ($book === NULL)  {
				$this->forward("404");
			}
			$this->getTemplate()->book = $book;

			// Other titles
			$rows = Leganto::books()->getSelector()->findOthers($book);
			$this->getTemplate()->other = array();
			while($entity = Leganto::books()->fetchAndCreate($rows)) {
				$this->getTemplate()->other[] = $entity;
			}

			// Authors
			$rows = Leganto::authors()->getSelector()->findAllByBook($book);
			$this->getTemplate()->authors = array();
			while($entity = Leganto::authors()->fetchAndCreate($rows)) {
				$this->getTemplate()->authors[] = $entity;
			}

			// Tags
			$rows = Leganto::tags()->getSelector()->findAllByBook($book);
			$this->getTemplate()->tags = array();
			while($entity = Leganto::tags()->fetchAndCreate($rows)) {
				$this->getTemplate()->tags[] = $entity;
			}

		}
		catch (DataNotFoundException $e) {
			Debug::processException($e);
			$this->forward("404");
		}
		catch(DibiDriverException $e) {
			Debug::processException($e);
			$this->forward("500");
		}
	}

	public function renderOpinions($book, $offset = 0, $limit = 10) {
		if (empty($book)) {
			$this->forward("404");
		}
		if ($limit > 100) {
			$limit = 100;
		}
		try {
			// Book
			$this->getTemplate()->book = Leganto::books()->getSelector()->find($book);
			if ($this->getTemplate()->book === NULL)  {
				$this->forward("404");
			}

			// Authors
			$rows = Leganto::authors()->getSelector()->findAllByBook($this->getTemplate()->book);
			$this->getTemplate()->authors = array();
			while($entity = Leganto::authors()->fetchAndCreate($rows)) {
				$this->getTemplate()->authors[] = $entity;
			}

			// Opinions
			$rows = Leganto::opinions()->getSelector()->findAllByBook($this->getTemplate()->book)->applyLimit($limit, $offset);
			$this->getTemplate()->opinions = array();
			while ($opinion = Leganto::opinions()->fetchAndCreate($rows)) {
				$this->getTemplate()->opinions[] = $opinion;
			}
		}
		catch(DibiDriverException $e) {
			Debug::processException($e);
			$this->forward("500");
		}
	}

	public function renderPosts($id, $type, $offset = 0, $limit = 10) {
		if ($limit > 100) {
			$limit = 100;
		}
		try {
			$rows = Leganto::posts()->getSelector()->findAllByIdAndType($id, $type);
			$this->getTemplate()->posts = array();
			while($post = Leganto::posts()->fetchAndCreate($rows)) {
				$this->getTemplate()->posts[] = $post;
			}
		}
		catch(NullPointerException $e) {
			Debug::processException($e);
			$this->forward("404");
		}
		catch(DibiDriverException $e) {
			Debug::processException($e);
			$this->forward("500");
		}
	}

	public function renderShelf($id, $offset = 0, $limit = 10) {
		if (empty($id)) {
			$this->forward("404");
		}
		if ($limit > 100) {
			$limit = 100;
		}
		try {
			// Shelf
			$this->getTemplate()->shelf = Leganto::shelves()->getSelector()->find($id);
			if ($this->getTemplate()->shelf === NULL)  {
				$this->forward("404");
			}

			// Books
			$rows = Leganto::books()->getSelector()->findAllByShelf($this->getTemplate()->shelf)->applyLimit($limit, $offset);
			$this->getTemplate()->books = Leganto::books()->fetchAndCreateAll($rows);
		}
		catch(DibiDriverException $e) {
			Debug::processException($e);
			$this->forward("500");
		}
	}

	public function renderSimilarBooks($book, $limit = 0, $offset = 10) {
		if (empty($book)) {
			$this->forward("404");
		}
		if ($limit > 100) {
			$limit = 100;
		}
//		try {
			// Book
			$this->getTemplate()->book = Leganto::books()->getSelector()->find($book);
			if ($this->getTemplate()->book === NULL)  {
				$this->forward("404");
			}

			// Similar books
			$rows = Leganto::books()->getSelector()->findAllSimilar($this->getTemplate()->book)->applyLimit($offset,$limit);
			$this->getTemplate()->similar = Leganto::books()->fetchAndCreateAll($rows);
//		}
//		catch(DibiDriverException $e) {
//			Debug::processException($e);
//			$this->forward("500");
//		}
	}

	public function renderUser($id) {
		if (empty($id)) {
			$this->forward("404");
		}
		try {
			// User's info
			$user = Leganto::users()->getSelector()->find($id);
			if ($user == NULL) {
				$this->forward("404");
			}
			$this->getTemplate()->user = $user;
			// User's shelfs
			$rows = Leganto::shelves()->getSelector()->findByUser($user);
			$this->getTemplate()->shelves = array();
			while($entity = Leganto::shelves()->fetchAndCreate($rows)) {
				$this->getTemplate()->shelves[] = $entity;
			}
		}
		catch (DataNotFoundException $e) {
			Debug::processException($e);
			$this->forward("404");
		}
		catch(DibiDriverException $e) {
			Debug::processException($e);
			$this->forward("500");
		}
	}

}