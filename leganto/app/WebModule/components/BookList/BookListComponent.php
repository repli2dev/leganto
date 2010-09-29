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
class BookListComponent extends BaseListComponent {

	// ---- PROTECTED METHODS

	protected function beforeRender() {
		parent::beforeRender();
		$this->loadTemplate($this->getSource());
	}

	protected function startUp() {
		parent::startUp();
		$this->setLimit(12);
	}

	// ---- PRIVATE METHODS

	private function loadTemplate(DibiDataSource $source) {
		$paginator = $this->getPaginator();
		if ($this->getLimit() == 0) {
			$paginator->itemsPerPage = $paginator->itemCount;
		}
		$books = $source->applyLimit($paginator->itemsPerPage, $paginator->offset);
		$this->getTemplate()->books = array();
		$this->getTemplate()->covers = array();
		$this->getTemplate()->tags = array();
		$this->getTemplate()->authors = array();
		// Books and covers
		$storage = new EditionImageStorage();
		foreach ($books as $book) {
			// Book
			$entity = Leganto::books()->createEmpty()->loadDataFromArray($book->toArray(), "Load");
			$this->getTemplate()->books[] = $entity;
			// Cover
			$image = $storage->getRandomFileByBook($entity);
			$this->getTemplate()->covers[$entity->getId()] = empty($image) ? NULL : $image->getAbsolutePath();
		}
		// Authors
		$nodes = array();
		foreach ($this->getTemplate()->books AS $book) {
			$nodes[$book->bookNode] = 1;
		}
		if (empty($nodes)) {
			$authors = array();
		} else {
			$authors = Leganto::authors()->getSelector()->findAllByBooks(array_keys($nodes))
					->fetchAssoc("id_book,id_author");
		}
		foreach ($authors as $bookId => $authorGroup) {
			$this->getTemplate()->authors[$bookId] = array();
			foreach ($authorGroup AS $author) {
				$entity = Leganto::authors()->createEmpty()->loadDataFromArray($author->toArray(), "Load");
				$this->getTemplate()->authors[$bookId][] = $entity;
			}
		}
	}

}
