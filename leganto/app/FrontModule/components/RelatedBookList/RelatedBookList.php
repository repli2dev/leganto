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
use Leganto\DB\Factory,
 Leganto\Storage\EditionImageStorage;

class RelatedBookList extends BaseListComponent {

	protected function beforeRender() {
		// Books and covers
		$this->getTemplate()->books = array();
		$this->getTemplate()->covers = array();
		$storage = new EditionImageStorage();
		while ($book = Factory::book()->fetchAndCreate($this->getSource())) {
			$this->getTemplate()->books[] = $book;
			$this->getTemplate()->covers[$book->getId()] = $storage->getRandomFileByBook($book);
		}
	}

}