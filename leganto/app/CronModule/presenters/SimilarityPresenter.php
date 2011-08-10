<?php

/**
 * Cron user maintanance
 * @author Jan Papousek
 * @author Jan Drabek
 */

namespace CronModule;

use Leganto\DB\Similarity\DatabaseSimilarity;

class Cron_SimilarityPresenter extends Cron_BasePresenter {

	public function renderBooks() {
		$similarity = new DatabaseSimilarity("tagged", "book", "tag");
		$similarity->setConnection($this->connection);
		$similarity->checkout();
	}

	public function renderUsers() {
		$similarity = new DatabaseSimilarity("opinion", "user", "book_title", "rating", 5);
		$similarity->setConnection($this->connection);
		$similarity->checkout();
	}

}
