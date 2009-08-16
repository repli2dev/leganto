<?php
/**
 * @Tag Jan Papousek
 */
class TagFactory extends AEntityFactory
{

	/** @return TagEntity */
	public function createEmpty() {
		return new TagEntity();
	}

	protected function createInserter() {
		return new TagInserter();
	}

	protected function createUpdater() {
		return new TagUpdater();
	}
	
	protected function createSelector() {
		return new TagSelector();
	}
	
	protected function createDeleter() {
		return new TagDeleter();
	}

}
