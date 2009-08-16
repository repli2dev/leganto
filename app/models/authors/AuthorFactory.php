<?php
/**
 * @author Jan Papousek
 */
class AuthorFactory extends AEntityFactory
{

	public function all() {
		return dibi::dataSource("SELECT * FROM [author]");
	}

	/** @return AuthorEntity */
	public function createEmpty() {
		return new AuthorEntity();
	}

	protected function createInserter() {
		return new AuthorWorker();
	}

	protected function createUpdater() {
		return new AuthorWorker();
	}

	/** @return DataSource */
	public function findAllByBook(BookEntity $book) {
		if (empty($book)) {
			throw new NullPointerException("book");
		}
		return dibi::dataSource("SELECT * FROM [view_book_author]")
			->where("[id_book] = %i", $book->bookNode);
	}

	/** @return AuthorEntity */
	public function one($id) {
		$row = dibi::dataSource("SELECT * FROM [author] WHERE [id_author] = %i", $id)->fetch();
		return empty($row) ? NULL : $this->createEmpty()->loadDataFromRow($row);
	}

}
