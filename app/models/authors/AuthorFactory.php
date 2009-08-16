<?php
/**
 * @author Jan Papousek, Jan Drabek
 */
class AuthorFactory extends AEntityFactory
{
	/** @return AuthorEntity */
	public function createEmpty() {
		return new AuthorEntity();
	}

	protected function createInserter() {
		return new AuthorInserter();
	}

	protected function createUpdater() {
		return new AuthorUpdater();
	}
	
	protected function createSelector() {
		return new AuthorSelector();
	}
	
	protected function createDeleter() {
		return new AuthorDeleter();
	}

}
