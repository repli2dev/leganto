<?php
/**
 * @author Jan Papousek
 */
class BookWorker extends Worker implements IInserter, IUpdater
{

	/* PUBLIC METHODS */

	public function insert(BookEntity $entity) {
		if (!$entity->isReadyToInsert()) {
			throw new InvalidArgumentException("The entity is not ready to be inserted.");
		}
		// First I try to find the book
		$books = Leganto::books()->all()
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

	public function update(BookEntity $entity) {
		if (!$entity->isReadyToUpdate()) {
			throw new InvalidArgumentException("The entity is not ready to be updated.");
		}
		$input = $this->getArrayFromEntity($entity, "Save");
		SimpleTableModel::createTableModel("book_title")->update($entity->getId(), $input);
	}

	/* PROTECTED METHODS */

	protected function createModel() {
		throw new NotSupportedException();
	}
}