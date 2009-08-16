<?php
/**
 * @author Jan Drabek
 */
class BookSelector extends Worker implements IBookSelector
{

	/* PUBLIC METHODS */
	
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [view_book]");
	}
	
	public function findAllByAuthor(IEntity $author) {
		if (empty($author)) {
			throw new NullPointerException("author");
		}
		return dibi::dataSource("SELECT * FROM [view_author_book] WHERE [id_author] = %i", $author->getId())
				->orderBy(array("id_book","title"));
	}

	public function findOthers(BookEntity $book) {
		if (empty($book)) {
			throw new NullPointerException("book");
		}
		return $this->findAll()
			->where("[id_book] = %i", $book->bookNode)
			->where("[id_book_title] != %i", $book->getId())
			->orderBy("title");

	}

	/** @return BookEntity */
	public function findOne($id) {
		$row = dibi::dataSource("SELECT * FROM [view_book] WHERE [id_book_title] = %i", $id)->fetch();
		$entity = new BookEntity;
		return empty($row) ? NULL : $entity->loadDataFromRow($row);
	}

	/* PROTECTED METHODS */

	protected function createModel() {
		throw new NotSupportedException();
	}
}