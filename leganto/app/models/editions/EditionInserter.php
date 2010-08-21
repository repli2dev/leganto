<?php
class EditionInserter implements IInserter
{

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
			$entity = Leganto::editions()->createEmpty();
			$entity->idBookTitle	= $book->getId();
			$entity->pages			= $edition[GoogleBooksEditionFinder::PAGES];
			$entity->published		= $edition[GoogleBooksEditionFinder::PUBLISHED];
			if(isSet($edition[GoogleBooksEditionFinder::IDENTIFIER][1])) {
				$isbn			= strtr($edition[GoogleBooksEditionFinder::IDENTIFIER][1], array("ISBN:" => ""));
				if (strlen($isbn) == 10) {
					$entity->isbn10	= $isbn;
				}
			}
			if(isSet($edition[GoogleBooksEditionFinder::IDENTIFIER][2])) {
				$isbn 		= strtr($edition[GoogleBooksEditionFinder::IDENTIFIER][2], array("ISBN:" => ""));
				if (strlen($isbn) == 13 ) {
					$entity->isbn13	= $isbn;
				}
			}
			$entity->inserted		= new DateTime();
			if ($entity->isbn10 == NULL || $entity->isbn13 == NULL) continue;
			$entity->persist();
			if ($entity->getId() != -1) {
				$result[] = $entity;
			}
		}
		return $result;
	}

}
