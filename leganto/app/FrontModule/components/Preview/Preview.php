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
use \Leganto\DB\Factory,
    Leganto\Storage\EditionImageStorage;
class Preview extends BaseComponent {

	public function render() {
		$opinions = Factory::opinions()
				->getSelector()
				->findAllNotEmptyLastUniqueBook()
				->fetchAll();
		$this->getTemplate()->opinions = array();
		foreach ($opinions AS $opinion) {
			$entity = Factory::opinions()->createEmpty()->loadDataFromArray($opinion->toArray(), "Load");
			$this->getTemplate()->opinions[$entity->bookTitleId] = $entity;
		}
		$this->getTemplate()->books = Factory::books()->fetchAndCreateAll(
					Factory::books()->getSelector()
					->findAll()
					->where("id_book_title IN %l", array_keys($this->getTemplate()->opinions))
		);
		$storage = new EditionImageStorage();
//		$this->getTemplate()->covers = array();
//		foreach ($this->getTemplate()->books AS $book) {
//			$image = $storage->getRandomFileByBook($book);
//			$this->getTemplate()->covers[$book->getId()] = empty($image) ? NULL : $image->getAbsolutePath();
//		}
		parent::render();
	}

}

