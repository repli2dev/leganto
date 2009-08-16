<?php
/**
 * @author Jan Drabek
 */
class AuthorSelector extends Worker implements IAuthorSelector
{

	/* PUBLIC METHODS */
	
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [author]");
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
	public function findOne($id) {
		$row = dibi::dataSource("SELECT * FROM [author] WHERE [id_author] = %i", $id)->fetch();
		$entity = new AuthorEntity;
		return empty($row) ? NULL : $entity->loadDataFromRow($row);
	}

	/* PROTECTED METHODS */

	protected function createModel() {
		return SimpleTableModel::createTableModel("author");
	}
}