<?php

/**
 * Book updater
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\Book;

use Leganto\ORM\Workers\IUpdater,
    Leganto\DB\Factory,
    Leganto\ORM\SimpleEntityFactory,
    Leganto\ORM\SimpleTableModel,
    Leganto\ORM\Workers\AWorker,
    Leganto\ORM\IEntity;

class Updater extends AWorker implements IUpdater {

	public function merge(\Leganto\DB\Book\Entity $superior, \Leganto\DB\Book\Entity $inferior) {

		// book_title, tagged, in_shelf, opinion, book_similarity

		$this->connection->begin();

		$supIds["tags"] = $this->connection->query("SELECT [id_tag] FROM [tagged] WHERE [id_book] = %i", $superior->bookNode)->fetchPairs("id_tag", "id_tag");
		$supIds["opinions"] = $this->connection->query("SELECT [id_user] FROM [opinion] WHERE [id_book_title] = %i", $superior->getId())->fetchPairs("id_user", "id_user");
		$supIds["shelves"] = $this->connection->query("SELECT [id_shelf] FROM [in_shelf] WHERE [id_book_title] = %i", $superior->getId())->fetchPairs("id_shelf", "id_shelf");

		$mergeQueries["tags"] = $this->connection->update("tagged", array("id_book" => $superior->bookNode))->where("[id_book] = %i", $inferior->bookNode);
		$mergeQueries["opinions"] = $this->connection->update("opinion", array("id_book_title" => $superior->getId()))->where("[id_book_title] = %i", $inferior->getId());
		$mergeQueries["shelves"] = $this->connection->update("in_shelf", array("id_book_title" => $superior->getId()))->where("[id_book_title] = %i", $inferior->getId());

		$cleanQueries["tags"] = $this->connection->delete("tagged")->where("[id_book] = %i", $inferior->bookNode);
		$cleanQueries["opinions"] = $this->connection->delete("opinion")->where("[id_book_title] = %i", $inferior->getId());
		$cleanQueries["shelves"] = $this->connection->delete("in_shelf")->where("[id_book_title] = %i", $inferior->getId());

		$conflictCols["tags"] = "id_tag";
		$conflictCols["opinions"] = "id_user";
		$conflictCols["shelves"] = "id_shelf";

		foreach ($mergeQueries AS $type => $query) {
			if (!empty($supIds[$type])) {
				$query->where("[" . $conflictCols[$type] . "] NOT IN %l", $supIds[$type]);
			}
			$query->execute();
			$cleanQueries[$type]->execute();
		}

		$this->connection->delete("book_title")->where("[id_book_title] = %i", $inferior->getId())->execute();

		$titlesOfBook = $this->connection->dataSource("SELECT [id_book_title] FROM [book_title] WHERE [id_book] = %i", $inferior->bookNode);
		// titul je poslednim titulem daneho knizniho uzlu
		if ($superior->bookNode != $inferior->bookNode && $titlesOfBook->count() == 0) {
			$this->connection->delete("book")->where("[id_book] = %i", $inferior->bookNode)->execute();
		}

		$this->connection->commit();
	}

	/**
	 * It tags a book
	 *
	 * @param \Leganto\DB\Book\Entity $book
	 * @param array|\Leganto\DB\Tag\Entity $tag
	 * @throws InvalidArgumentException is there are no tags, or they are not of right instances
	 */
	public function setTagged(\Leganto\DB\Book\Entity $book, $tagged) {
		if (!is_array($tagged) && !($tagged instanceof \Leganto\DB\Tag\Entity)) {
			throw new InvalidArgumentException("The argument [tagged] has to be array or TagEntity.");
		}
		// Find all tags
		$tags = Factory::tag()->getSelector()->findAllByBook($book)->fetchPairs("id_tag", "id_tag");
		if ($tagged instanceof \Leganto\DB\Tag\Entity) {
			$tagged = array($tagged);
		}
		$this->connection->begin();
		// Add new relations
		foreach ($tagged AS $tag) {
			if (!in_array($tag->getId(), $tags)) {
				$this->connection->insert("tagged", array(
				    "id_tag" => $tag->getId(),
				    "id_book" => $book->bookNode
				))->execute();
			}
		}
		$this->connection->commit();
	}

	/**
	 * It sets author(s) as an author of the book
	 *
	 * @param \Leganto\DB\Book\Entity $book
	 * @param array|\Leganto\DB\Author\Entity $writtenBy
	 * @throws InvalidArgumentException if the $writtenBy is not an \Leganto\DB\Author\Entity (array)
	 */
	public function setWrittenBy(\Leganto\DB\Book\Entity $book, $writtenBy) {
		if ($book->getState() != IEntity::STATE_PERSISTED) {
			throw new InvalidArgumentException("The entity is not ready to be updated.");
		}
		// I want to add a set of authors
		if (is_array($writtenBy)) {
			$this->connection->begin();
			// delete all old relations between the book and authors
			$this->connection->delete("written_by")->where("[id_book] = %i", $book->bookNode)->execute();
			// add new relations
			foreach ($writtenBy as $author) {
				$this->connection->insert("written_by", array(
				    "id_book" => $book->bookNode,
				    "id_author" => $author->getId()
					)
				)->execute();
			}
			$this->connection->commit();
		}
		// I want to add only one author
		else if ($writtenBy instanceof \Leganto\DB\Author\Entity) {
			$this->connection->insert("written_by", array(
			    "id_book" => $book->bookNode,
			    "id_author" => $writtenBy->getId()
				)
			)->execute();
		} else {
			throw new InvalidArgumentException("The argument [writtenBy] has to be array or AuthorEntity.");
		}
	}

	public function update(IEntity $entity) {
		if ($entity->getState() != IEntity::STATE_MODIFIED) {
			throw new InvalidArgumentException("The entity can not be inserted because it is not in state [MODIFIED].");
		}
		$input = $entity->getData("Save");
		SimpleTableModel::createTableModel("book_title",$this->connection)->update($entity->getId(), $input);
	}

}