<?php
/**
 * @author Jan Papousek
 */
class ShelfSelector implements ISelector
{

	public function findAll() {
		return dibi::dataSource("SELECT * FROM [view_shelf]");
	}

	public function findByBook(BookEntity $book) {
		if ($book->getId() == NULL) {
			throw new NullPointerException("The book has no ID");
		}
		return dibi::dataSource("SELECT * FROM [view_shelf] WHERE [id_book] = %i", $book->getId());
	}

	public function findByUser(UserEntity $user) {
		if ($user->getId() == NULL) {
			throw new NullPointerException("The user has no ID");
		}
		return dibi::dataSource("SELECT * FROM [view_shelf] WHERE [id_user] = %i", $user->getId());
	}

	public function find($id) {
		return Leganto::shelves()
			->fetchAndCreate(
				dibi::dataSource("SELECT * FROM [view_shelf] where [id_shelf] = %i", $id)
			);
	}

}
