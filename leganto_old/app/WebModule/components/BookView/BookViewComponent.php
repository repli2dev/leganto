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
class BookViewComponent extends BaseComponent {

	private $book;
	private $edition;

	public function getEditionId() {
		return $this->edition;
	}

	public function setBook(BookEntity $book) {
		$this->book = $book;
	}

	public function setEditionId($edition) {
		$this->edition = $edition;
	}

	protected function beforeRender() {
		if (empty($this->book)) {
			throw new InvalidArgumentException("The component [$name] can not be rendered because the book is not set.");
		}
		$this->getTemplate()->book = $this->book;
		// Authors
		$this->getTemplate()->authors = Leganto::authors()->fetchAndCreateAll(
				Leganto::authors()->getSelector()->findAllByBook($this->book)
		);
		// Cover
		$storage = new EditionImageStorage();
		$this->getTemplate()->cover = $storage->getRandomFileByBook($this->book);
		// Edition?
		if (!empty($this->edition)) {
			$edition = Leganto::editions()->getSelector()->find($this->edition);
			$this->getTemplate()->edition = $edition;
		}
	}

	protected function createComponentBookStatistics($name) {
		$stats = new BookStatisticsComponent($this, $name);
		$stats->setBook($this->book);
		return $stats;
	}

}
