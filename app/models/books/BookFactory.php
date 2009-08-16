<?php

/**
 * @author Jan Papousek, Jan Drabek
 */
class BookFactory extends AEntityFactory
{

	/** @return BookEntity */
	public function createEmpty() {
		return new BookEntity();
	}

	protected function createInserter() {
		return new BookInserter();
	}

	protected function createUpdater() {
		return new BookUpdater();
	}
	
	protected function createSelector() {
		return new BookSelector();
	}
	
	protected function createDeleter() {
		return new BookDeleter();
	}

}
