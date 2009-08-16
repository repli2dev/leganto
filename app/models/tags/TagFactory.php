<?php
/**
 * @Tag Jan Papousek
 */
class TagFactory extends AEntityFactory
{

	public function all() {
		return dibi::dataSource("SELECT * FROM [tag]");
	}

	/** @return TagEntity */
	public function createEmpty() {
		return new TagEntity();
	}

	protected function createInserter() {
		return new TagWorker();
	}

	protected function createUpdater() {
		return new TagWorker();
	}

	/** @return DataSource */
	public function findAllByBook(BookEntity $book) {
		if (empty($book)) {
			throw new NullPointerException("book");
		}
		return dibi::dataSource("SELECT * FROM [view_book_tag]")
			->where("[id_book] = %i", $book->getId())
			->where("[id_language] = %i", $book->languageId);
	}

	/** @return TagEntity */
	public function one($id) {
		$row = dibi::dataSource("SELECT * FROM [tag] WHERE [id_tag] = %i", $id)->fetch();
		return empty($row) ? NULL : $this->createEmpty()->loadDataFromRow($row);
	}

	/**
	 * It tags a book
	 *
	 * @param int $book Book ID
	 * @param int $tag Tag ID
	 */
	public function setTagged($book, $tag) {
		$rows = SimpleTableModel::createTableModel("tagged")
			->findAll()
			->where("[id_book] = %i", $book)
			->where("[id_tag] = %i", $tag);
		if ($rows->count() == 0) {
			SimpleTableModel::createTableModel("tagged")->insert(array(
				"id_book" => $book,
				"id_tag" => $tag
			));
		}
	}

}
