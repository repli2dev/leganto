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
namespace Leganto\DB\Edition;
use Leganto\ORM\Workers\IInserter,
	Leganto\DB\Factory,
	Leganto\ORM\Workers\SimpleInserter;

class Inserter implements IInserter {

	public function insert(IEntity &$entity) {
		return SimpleInserter::createInserter("edition")->insert($entity);
	}

	/**
	 * It inserts new editions specified by array
	 * which is produced by GoogleBooksBookFinder
	 *
	 * @param IEntity The book which the edition is related to.
	 * @param array $info
	 * @return array Array of Edition entities
	 * @see GoogleBooksEditionFinder
	 */
	public function insertByGoogleBooksInfo(IEntity $book, array $info) {
		$result = array();
		foreach ($info AS $edition) {
			$entity = Factory::editions()->createEmpty();
			$entity->idBookTitle = $book->getId();
			$entity->pages = $edition[GoogleBooksEditionFinder::PAGES];
			$entity->published = $edition[GoogleBooksEditionFinder::PUBLISHED];
			if (isSet($edition[GoogleBooksEditionFinder::IDENTIFIER][1])) {
				$isbn = strtr($edition[GoogleBooksEditionFinder::IDENTIFIER][1], array("ISBN:" => ""));
				if (strlen($isbn) == 10) {
					$entity->isbn10 = $isbn;
				}
			}
			if (isSet($edition[GoogleBooksEditionFinder::IDENTIFIER][2])) {
				$isbn = strtr($edition[GoogleBooksEditionFinder::IDENTIFIER][2], array("ISBN:" => ""));
				if (strlen($isbn) == 13) {
					$entity->isbn13 = $isbn;
				}
			}
			$entity->inserted = new DateTime();
			if ($entity->isbn10 == NULL || $entity->isbn13 == NULL)
				continue;
			$entity->persist();
			if ($entity->getId() != -1) {
				$result[] = $entity;
			}
		}
		return $result;
	}

	public function insertByYazRecords(IEntity $book, array $records) {
		$result = array();
		foreach($records AS $record) {
			$entity = Factory::editions()->createEmpty();
			$entity->idBookTitle = $book->getId();
			$entity->pages = $record->getData()->pages;
			if (isset($record->getData()->publushedYear)) {
				$entity->published = $record->getData()->publushedYear;
			}
			$isbn = strtr($record->getData()->isbn, array("-" => ""));
			if (strlen($isbn) === 10) {
				$entity->isbn10 = $isbn;
			}
			else {
				$entity->isbn13 = $isbn;
			}
			$entity->inserted = new DateTime();
			$entity->persist();
			if ($entity->getId() != -1) {
				$result[] = $entity;
			}
		}
		return $result;
	}

}
