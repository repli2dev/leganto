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
	 * @see GoogleBooksBookFinder
	 */
	public function insertByGoogleBooksInfo(IEntity $book, array $info) {
		$result = array();
		foreach ($info AS $edition) {
			foreach($edition[GoogleBooksBookFinder::IDENTIFIER] AS $identifier) {
				if (!preg_match("/ISBN/", $identifier)) {
					continue;
				}
				$entity = Leganto::editions()->createEmpty();
				$entity->idBookTitle	= $book->getId();
				$entity->pages			= $edition[GoogleBooksBookFinder::PAGES];
				$entity->published		= $edition[GoogleBooksBookFinder::PUBLISHED];
				$entity->isbn			= strtr($identifier, array("ISBN:" => ""));
				$entity->inserted		= new DibiVariable("now()", "sql");
				$entity->persist();
				if ($entity->getId() != -1) {
					$result[] = $entity;
				}
			}
		}
		return $result;
	}

}
