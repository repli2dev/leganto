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
class BookInserter extends Worker implements IInserter
{

	/* PUBLIC METHODS */
	
	public function insert(BookEntity $entity) {
		if (!$entity->isReadyToInsert()) {
			throw new InvalidArgumentException("The entity is not ready to be inserted.");
		}
		// First I try to find the book
		$books = Leganto::books()->getInserter()->findAll()
			->where("[title] = %s", $entity->title)
			->where("[id_language] = %s", $entity->languageId)
			->fetchAll();
		// TODO: check the duplicity

		// Save the general book entity
		$bookId = SimpleTableModel::createTableModel("book")->insert(array("inserted" => new DibiVariable("now()")));
		// Save book author
		foreach ($entity->getAuthors() AS $author) {
			$authodId = Leganto::authors()->getInserter()->insert($author);
			SimpleTableModel::createTableModel("written_by")->insert(array(
				"id_book"	=> $bookId,
				"id_author"	=> $authodId
			));
		}
		foreach($entity->getTags() AS $tag) {
			$tagId = Leganto::tags()->getInserter()->insert($tag);
			Leganto::tags()->setTagged($bookId, $tagId);
		}
	}
	
}