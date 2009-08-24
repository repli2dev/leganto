<?php
/**
 * @author Jan Papousek
 */
class ViewPresenter extends Presenter
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

	public function renderBookOpinions($book) {
		if (empty($book)) {
			$this->forward("404");
		}
		try {
			
		}
		catch(DibiDriverException $e) {
			Debug::processException($e);
			$this->forward("500");
		}
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

	/* ERRORS */

	public function render500() {
		ob_clean();
		Header("Content-type: text/plain");
		Header("HTTP/1.0 500 Internal server error");
		die();
	}

	public function render404() {
		ob_clean();
		Header("Content-type: text/plain");
		Header("HTTP/1.0 404 Not Found");
		die();
	}

	/* PROTECTED METHODS */

	protected function beforeRender() {
//		Header("Content-type: text/xml");
	}

	protected function createTemplate() {
		$this->oldLayoutMode = false;

		$template = parent::createTemplate();

		// register filters
		$template->registerFilter('CurlyBracketsFilter::invoke');

		// register custom helpers
		$template->registerHelper("date", Helpers::getHelper('date'));
		$template->registerHelper("time", Helpers::getHelper('time'));
		$template->registerHelper("texy", Helpers::getHelper('texy'));
		$template->registerHelper("translate", Helpers::getHelper('translate'));

		return $template;
	}

}