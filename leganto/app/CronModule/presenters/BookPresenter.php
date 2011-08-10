<?php

/**
 * Cron user maintanance
 * @author Jan Papousek
 * @author Jan Drabek
 */

namespace CronModule;

class BookPresenter extends BasePresenter {
	const NUMBER_OF_SIMILAR_BOOKS = 50;

	public function renderStoreSimilarBooks() {
		$this->connection->query("DROP TABLE IF EXISTS [tmp_similar_book]");
		$this->connection->query("CREATE TABLE [tmp_similar_book] (INDEX ([id_book_from]), INDEX ([id_book_title])) AS SELECT * FROM [view_similar_book]");
		$this->connection->query("DROP TABLE IF EXISTS [tmp_similar_book_prepared]");
		$this->prepareSimilarBooks(TRUE);
	}

	public function renderPrepareSimilarBooks() {
		$this->prepareSimilarBooks(FALSE);
	}

	private function prepareSimilarBooks($create = FALSE) {
		if ($create) {
			$books = $this->connection->query("SELECT DISTINCT([id_book_from]) FROM [tmp_similar_book] LIMIT 1000")->fetchPairs("id_book_from", "id_book_from");
		} else {
			$books = $this->connection->query("SELECT DISTINCT([id_book_from]) FROM [tmp_similar_book] WHERE [id_book_from] NOT IN (SELECT [id_book_from] FROM [tmp_similar_book_prepared]) LIMIT 1000")->fetchPairs("id_book_from", "id_book_from");
		}
		if (empty($books))
			return;
		$queries = array();
		foreach ($books AS $book) {
			$queries[] = "(SELECT * FROM [tmp_similar_book] WHERE [id_book_from] = $book LIMIT " . self::NUMBER_OF_SIMILAR_BOOKS . ")";
		}
		$select = implode($queries, " UNION ");
		if ($create) {
			$this->connection->query("CREATE TABLE [tmp_similar_book_prepared] (INDEX ([id_book_from]), INDEX ([id_book_title])) AS $select");
		} else {
			$this->connection->query("INSERT INTO [tmp_similar_book_prepared] $select");
		}
	}

}
