<?php

/**
 * @author Jan Papousek
 */
class BookFactory extends AEntityFactory
{

	public function all() {
		return dibi::dataSource("SELECT * FROM [view_book]");
	}

	/** @return BookEntity */
	public function createEmpty() {
		return new BookEntity();
	}

	protected function createInserter() {
		return new BookWorker();
	}

	protected function createUpdater() {
		return new BookWorker();
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
		return $this->all()
			->where("[id_book] = %i", $book->bookNode)
			->where("[id_book_title] != %i", $book->getId())
			->orderBy("title");

	}

	/** @return BookEntity */
	public function one($id) {
		$row = dibi::dataSource("SELECT * FROM [view_book] WHERE [id_book_title] = %i", $id)->fetch();
		return empty($row) ? NULL : $this->createEmpty()->loadDataFromRow($row);
	}

}
