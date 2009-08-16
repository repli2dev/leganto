<?php
/**
 * @author Jan Papousek
 */
class UserFactory extends AEntityFactory
{

	/** @return UserEntity */
	public function createEmpty() {
		return new UserEntity();
	}

	protected function createInserter() {
		return new UserInserter();
	}

	protected function createUpdater() {
		return new UserUpdater();
	}
	
	protected function createSelector() {
		return new UserSelector();
	}
	
	protected function createDeleter() {
		return new UserDeleter();
	}

}
