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
class BookUpdater implements IUpdater
{

	/* PUBLIC METHODS */

	public function merge(BookEntity $superior, BookEntity $inferior) {
		// Smazat vztahy s autory s podrizenou knihou
		// book_title, tagged, in_shelf, opinion, book_similarity
		dibi::begin();
		// Delete relations between authors and inferior book
		dibi::delete("written_by")
			->where("[id_book] = %i", $inferior->bookNode)
			->execute();
		// Move inferior book titles to superior book
		dibi::update("book_title", array("id_book" => $superior->bookNode))
			->where("[id_book] = %i", $inferior->bookNode)
			->execute();
		// Move inferior book tags to superior book
		dibi::query("
			UPDATE [tagged] SET
				[id_book] = %i", $superior->bookNode ,"
			WHERE
				[id_book] = %i", $inferior->bookNode ,"
			AND
				[id_tag] NOT IN (SELECT [id_tag] FROM [tagged] WHERE [id_book] = %i",$superior->bookNode,")
		");
		// Delete tags which overlapped
		dibi::delete("tagged")
			->where("[id_book] = %i", $inferior->bookNode)
			->execute();
		// Set the inferior book in shelves as superior book
		dibi::query("
			UPDATE [in_shelf] SET
				[id_book] = %i", $superior->bookNode ,"
			WHERE
				[id_book] = %i", $inferior->bookNode ,"
			AND
				[id_shelf] NOT IN (SELECT [id_shelf] FROM [in_shelf] WHERE [id_book] = %i",$superior->bookNode,")
		");
		// Delete books in shelf which overlapped
		dibi::delete("in_shelf")
			->where("[id_book] = %i", $inferior->bookNode)
			->execute();
		// Move opinions from inferior to superior book
		dibi::query("
			UPDATE [opinion] SET
				[id_book] = %i", $superior->bookNode ,"
			WHERE
				[id_book] = %i", $inferior->bookNode ,"
			AND
				[id_user] NOT IN (SELECT [id_user] FROM [opinion] WHERE [id_book] = %i",$superior->bookNode,")
		");
		// Delete opinions which overlapped
		dibi::delete("opinion")
			->where("[id_book] = %i", $inferior->bookNode)
			->execute();

		// Delete book similarity entries
		dibi::delete("book_similarity")
			->where("[id_book_from] = %i", $inferior->bookNode, " OR [id_book_to] = %i",  $inferior->bookNode)
			->execute();
		// TODO: iniciovat novy vypocet podobnosti u knizky
		// Delete inferior book
		dibi::delete("book")
			->where("[id_book] = %i", $inferior->bookNode)
			->execute();
		dibi::commit();
	}

	/**
	 * It tags a book
	 *
	 * @param BookEntity $book
	 * @param array|TagEntity $tag
	 */
	public function setTagged(BookEntity $book, $tagged) {
		if (!is_array($tagged) && !($tagged instanceof TagEntity)) {
			throw new InvalidArgumentException("The argument [tagged] has to be array or TagEntity.");
		}
		// Find all tags
		$tags = Leganto::tags()->getSelector()->findAllByBook($book)->fetchPairs("id_tag", "id_tag");
		if ($tagged instanceof TagEntity) {
			$tagged = array($tagged);
		}
		dibi::begin();
		// Add new relations
		foreach($tagged AS $tag) {
			if (!in_array($tag->getId(), $tags)) {
				dibi::insert("tagged", array(
					"id_tag"	=> $tag->getId(),
					"id_book"	=> $book->bookNode
				))->execute();
			}
		}
		dibi::commit();
	}

	/**
	 * It sets author(s) as an author of the book
	 *
	 * @param BookEntity $book
	 * @param array|AuthorEntity $writtenBy
	 * @throws InvalidArgumentException if the $writtenBy is not an AuthorEntity (array)
	 */
	public function setWrittenBy(BookEntity $book, $writtenBy) {
		if ($book->getState() != IEntity::STATE_PERSISTED) {
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

	public function update(IEntity $entity) {
		if ($entity->getState() != IEntity::STATE_MODIFIED) {
			throw new InvalidArgumentException("The entity can not be inserted because it is not in state [MODIFIED].");
		}
		$input = $entity->getData("Save");
		SimpleTableModel::createTableModel("book_title")->update($entity->getId(), $input);
	}

}