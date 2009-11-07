<?php
/**
 * The source file is subject to the license located on web
 * "http://code.google.com/p/preader/".
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (repli2dev@gmail.com)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 */

/**
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$Id$
 */
class ViewPresenter extends BasePresenter
{

	/* VIEWS */

	public function renderAuthor($id) {
		if (empty($id)) {
			$this->code(404, "Author's ID expected.");
		}
		try {
			$author = Leganto::authors()->getSelector()->find($id);
			if (empty($author)) {
				$this->code(404);
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
			$this->code(404, "The author does not exist.");
		}
		catch(DibiDriverException $e) {
			Debug::processException($e);
			$this->code(500, "Database error.");
		}
	}

	public function renderAvaiable() {}

	public function renderBook($id) {
		if (empty($id)) {
			$this->code(404, "Book ID expected.");
		}
		try {
			$book = Leganto::books()->getSelector()->find($id);
			if ($book === NULL)  {
				$this->code(404, "The book does not exist.");
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
			$this->code(404);
		}
		catch(DibiDriverException $e) {
			Debug::processException($e);
			$this->code(500, "Database error.");
		}
	}

	public function renderDiscussions($type = NULL, $offset = 0, $limit = 10) {
		if ($limit > 100) {
			$limit = 100;
		}
		try {
			$rows = Leganto::discussions()->getSelector()->findAll();
			if (!empty($type)) {
				$rows->where("[id_discussable] = %i", $type);
			}
			$this->getTemplate()->discussions = Leganto::discussions()->fetchAndCreateAll($rows->applyLimit($limit, $offset));
		}
		catch(DibiDriverException $e) {
			Debug::processException($e);
			$this->code(500, "Database error.");
		}
	}

	public function renderOpinions($book, $user = NULL, $offset = 0, $limit = 10) {
		if (empty($book)) {
			$this->code(404);
		}
		if ($limit > 100) {
			$limit = 100;
		}
		try {
			// Book
			$this->getTemplate()->book = Leganto::books()->getSelector()->find($book);
			if ($this->getTemplate()->book === NULL)  {
				$this->code(404);
			}

			// Authors
			$rows = Leganto::authors()->getSelector()->findAllByBook($this->getTemplate()->book);
			$this->getTemplate()->authors = array();
			while($entity = Leganto::authors()->fetchAndCreate($rows)) {
				$this->getTemplate()->authors[] = $entity;
			}

			// Opinions
			if (empty($user)) {
				$rows = Leganto::opinions()->getSelector()->findAllByBook($this->getTemplate()->book)->applyLimit($limit,$offset);
			}
			else {
				$userEntity = Leganto::users()->getSelector()->find($user);
				if ($userEntity == NULL) {
					$this->code(404);
				}
				$rows = Leganto::opinions()->getSelector()->findAllByBook($this->getTemplate()->book, $userEntity)->applyLimit($limit,$offset);
			}
			$this->getTemplate()->opinions = array();
			while ($opinion = Leganto::opinions()->fetchAndCreate($rows)) {
				$this->getTemplate()->opinions[] = $opinion;
			}
		}
		catch(DibiDriverException $e) {
			Debug::processException($e);
			$this->code(500, "Database error.");
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
			$this->code(404);
		}
		catch(DibiDriverException $e) {
			Debug::processException($e);
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
			$this->getTemplate()->shelf = Leganto::shelves()->getSelector()->find($id);
			if ($this->getTemplate()->shelf === NULL)  {
				$this->code(404);
			}

			// Books
			$rows = Leganto::books()->getSelector()->findAllByShelf($this->getTemplate()->shelf)->applyLimit($limit, $offset);
			$this->getTemplate()->books = Leganto::books()->fetchAndCreateAll($rows);
		}
		catch(DibiDriverException $e) {
			Debug::processException($e);
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
			$this->getTemplate()->book = Leganto::books()->getSelector()->find($book);
			if ($this->getTemplate()->book === NULL)  {
				$this->code(404);
			}

			// Similar books
			$rows = Leganto::books()->getSelector()->findAllSimilar($this->getTemplate()->book)->applyLimit($limit, $offset);
			$this->getTemplate()->similar = Leganto::books()->fetchAndCreateAll($rows);
		}
		catch(DibiDriverException $e) {
			Debug::processException($e);
			$this->code(500, "Database error.");
		}
	}

	public function renderSearchUsers($query, $offset = 0, $limit = 10){
	    if(empty($query)){
		    $this->code(404);
	    }
	    if($limit > 100){
		    $limit = 100;
	    }
	    try {
			$rows = Leganto::users()->getSelector()->search($query)->applyLimit($limit,$offset);
			$this->getTemplate()->users = Leganto::users()->fetchAndCreateAll($rows);
	    }
	    catch(DibiDriverException $e) {
			Debug::processException($e);
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
			$this->getTemplate()->user = Leganto::users()->getSelector()->find($user);
			if ($user == NULL) {
				throw new Exception();
				$this->code(404);
			}
			$rows = Leganto::users()->getSelector()->findAllSimilar($this->getTemplate()->user)->applyLimit($limit, $offset);
			$this->getTemplate()->users = Leganto::users()->fetchAndCreateAll($rows);
		}
		catch(DibiDriverException $e) {
			Debug::processException($e);
			$this->code(500, "Database error.");
		}
	}

	public function renderUser($id) {
		if (empty($id)) {
			$this->code(404);
		}
		try {
			// User's info
			$user = Leganto::users()->getSelector()->find($id);
			if ($user == NULL) {
				$this->code(404);
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
			$this->code(404);
		}
		catch(DibiDriverException $e) {
			Debug::processException($e);
			$this->code(500, "Database error.");
		}
	}
	
	public function renderSearchBooks($query, $offset = 0, $limit = 10){
		if (empty($query)) {
			$this->code(404);
		}
		try {
			// Book search
			$rows = Leganto::books()->getSelector()->search($query)->applyLimit($limit, $offset);
			$books = $rows->fetchAssoc("id_book_title");
			$authors = $rows->where("[id_book_title] IN %l", array_keys($books))->fetchAssoc("id_book_title,id_author");
			$this->getTemplate()->books = array();
			foreach($books as $book){
				$entity = Leganto::books()->createEmpty()->loadDataFromArray($book->getArrayCopy());
				$this->getTemplate()->books[] = $entity;
			}
			$this->getTemplate()->authors = array();
			foreach($authors as $bookTitleId => $authorGroup){
				$this->getTemplate()->authors[$bookTitleId] = array();
				foreach ($authorGroup AS $author) {
					$entity = Leganto::authors()->createEmpty()->loadDataFromArray($author->getArrayCopy());
					$this->getTemplate()->authors[$bookTitleId][] = $entity;
				}
				
			}
			
		}
		catch (DataNotFoundException $e) {
			throw new Exception;
			Debug::processException($e);
			$this->code(404);
		}
		catch(DibiDriverException $e) {
			Debug::processException($e);
			$this->code(500, "Database error.");
		}
	}

	public function actionBookCover($id) {
		$book = Leganto::books()->getSelector()->find($id);
		Debug::dump($book);
		$images = new BarvicBookImageFinder();
		Debug::dump($images->getImage($book));
		die();
	}

	public function actionBookISBN($id) {
		$book = Leganto::books()->getSelector()->find($id);
		Debug::dump($book);
		$isbn = new GoogleBooksFinder();
		Debug::dump($isbn->getISBN($book));
		die();
	}

	// FIXME: Proc ... nefunguje POST pole?!
	public function renderLogin() {
		// Load data
		$email = Environment::getHttpRequest()->getQuery("email");
		$password = Environment::getHttpRequest()->getQuery("password");

		// Login user
		if (empty($email) || empty($password)) {
			$this->permissionDenied();
		}
		try {
			Environment::getUser()->authenticate($email, $password);
		}
		catch (AuthenticationException $e) {
			Debug::processException($e);
			$this->permissionDenied();
		}
		
		// Create unique token
		$token = sha1(ExtraString::random(40));;
		$user = Environment::getUser()->getIdentity()->id_user;

		// Check if user is already logged
		$rows = SimpleTableModel::createTableModel("access")->findAll()->where("[id_user] = %i", $user)->count();
		if($rows == 0){ // There is already token in access table
			// Check for duplicity
			$tokens = SimpleTableModel::createTableModel("access")->findAll()->where("[token] = %s", $token)->count();
			if($tokens == 0){
				// Store token
				SimpleTableModel::createTableModel("access")->insert(
					array(
						"id_user"	=>	$user,
						"token"		=>	$token,
						"updated"	=>	time()
					)
				);
				// Publish token
				$this->getTemplate()->token = $token;
			} else {
			}
		} else { // Only print stored token
			$row = SimpleTableModel::createTableModel("access")->findAll()->where("[id_user] = %i", $user)->fetch();
			$this->getTemplate()->token = $row['token'];
		}
	}

	public function renderLogout() {
		Debug::dump(Environment::getUser()->getIdentity());
		die;
		// Remove token
		$user = Environment::getUser()->getIdentity()->id_user;
		SimpleTableModel::createTableModel("access")->deleteAll(array("id_user" => $user));
		// Destroy identity
		Environment::getUser()->signOut(TRUE);
		
	}

}