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

        public function findByUserAndBook(UserEntity $user, BookEntity $book) {
		if ($user->getId() == NULL) {
			throw new NullPointerException("The user has no ID");
		}
		if ($book->getId() == NULL) {
			throw new NullPointerException("The book has no ID");
		}
		// FIXME: Pohled view_shelf_book neni pripraven na to, aby se z nej delala entita Shelf
                return dibi::dataSource("SELECT * FROM [view_shelf_book] WHERE [id_user] = %i", $user->getId(), " AND [id_book_title] = %i", $book->getId());
        }

	public function find($id) {
		return Leganto::shelves()
			->fetchAndCreate(
				dibi::dataSource("SELECT * FROM [view_shelf] WHERE [id_shelf] = %i", $id)
			);
	}

}
