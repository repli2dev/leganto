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
class BookUpdater extends Worker implements IUpdater
{

	/* PUBLIC METHODS */
	
	public function update(IEntity $entity) {
		if (!$entity->isReadyToUpdate()) {
			throw new InvalidArgumentException("The entity is not ready to be updated.");
		}
		$input = $this->getArrayFromEntity($entity, "Save");
		SimpleTableModel::createTableModel("book_title")->update($entity->getId(), $input);
	}

	/**
	 * It sets author(s) as an author of the book
	 *
	 * @param BookEntity $book
	 * @param array|AuthorEntity $writtenBy
	 * @throws InvalidArgumentException if the $writtenBy is not an AuthorEntity (array)
	 */
	public function setWrittenBy(BookEntity $book, $writtenBy) {
		if (!$book->isReadyToUpdate()) {
			throw new InvalidArgumentException("The entity is not ready to be updated.");
		}
		// I want to add a set of authors
		if (is_array($writtenBy)) {
			dibi::begin();
			// delete all old relations between the book and authors
			dibi::delete("written_by")->where("[id_book] = %i",$book->bookNode)->execute();
			// add new relations
			foreach($writtenBy as $author){
				dibi::insert("written_by",
					array(
						"id_book" => $book->bookNode,
						"id_author" => $author->getId()
					)
				)->execute();
			}
			dibi::commit();
		}
		// I want to add only one author
		else if($writtenBy instanceof AuthorEntity) {
			SimpleTableModel::createTableModel("written_by")->insert(array(
				array(
					"id_book" => $book->bookNode,
					"id_author" => $author->getId()
				)
			));
		}
		else {
			throw new InvalidArgumentException("The argument [writtenBy] has to be array or AuthorEntity.");
		}
	
	}

	/**
	 * It tags a book
	 *
	 * @param BookEntity $book 
	 * @param array|TagEntity $tag
	 */
	public function setTagged(BookEntity $book, $tagged) {
		if (is_array($tagged)) {
			dibi::begin();
			// Delete old relations between tags and books
			dibi::delete("tagged")
				->where("[id_book] = %i", $book->bookNode)
				->execute();
			// Add new relations
			foreach($tagged AS $tag) {
				dibi::insert("tagged", array(
					"id_tag"	=> $tag->getId(),
					"id_book"	=> $book->getId()
				))->execute();
			}
			dibi::commit();
		}
		else if ($tagged instanceof TagEntity) {
			SimpleTableModel::createTableModel("tagged")->insert(array(
				"id_book" => $book,
				"id_tag" => $tag
			));
		}
		else {
			throw new InvalidArgumentException("The argument [tagged] has to be array or TagEntity.");
		}
	}

}