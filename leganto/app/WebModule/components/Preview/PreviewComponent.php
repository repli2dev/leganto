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
 *
 */

class PreviewComponent extends BaseComponent
{

	public function render() {
		 $opinions = Leganto::opinions()
		    ->getSelector()
		    ->findAllNotEmptyLast()
		    ->fetchAll();
		$this->getTemplate()->opinions = array();
		foreach($opinions AS $opinion) {
		    $entity = Leganto::opinions()->createEmpty()->loadDataFromArray($opinion->getArrayCopy(), "Load");
		    $this->getTemplate()->opinions[$entity->book] = $entity;
		}
		$this->getTemplate()->books = Leganto::books()->fetchAndCreateAll(
			Leganto::books()->getSelector()
				->findAll()
				->where("id_book IN %l", array_keys($this->getTemplate()->opinions))
		);
		$storage = new EditionImageStorage();
		$this->getTemplate()->covers = array();
		foreach ($this->getTemplate()->books AS $book) {
			$image = $storage->getRandomFileByBook($book);
			$this->getTemplate()->covers[$book->getId()] = empty($image) ? NULL : $image->getAbsolutePath();
		}
		parent::render();
	}

}

