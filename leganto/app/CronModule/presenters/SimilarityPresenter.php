<?php
class Cron_SimilarityPresenter extends Cron_BasePresenter
{

	public function renderBooks() {
		$similarity = new DatabaseSimilarity("tagged", "book", "tag");
		$similarity->checkout();
	}

	public function renderUsers() {
		$similarity = new DatabaseSimilarity("opinion", "user", "book_title", "rating", 5);
		$similarity->checkout();
	}

}
