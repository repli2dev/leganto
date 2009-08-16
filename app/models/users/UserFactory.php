<?php
/**
 * @author Jan Papousek
 */
class UserFactory extends AEntityFactory
{

	public function all() {
		return dibi::dataSource("SELECT * FROM [view_user]");
	}

	/** @return UserEntity */
	public function createEmpty() {
		return new UserEntity();
	}

	protected function createInserter() {
		return new UserWorker();
	}

	protected function createUpdater() {
		return new UserWorker();
	}

	/** @return UserEntity */
	public function one($id) {
		$row = dibi::dataSource("SELECT * FROM [view_user] WHERE [id_user] = %i", $id)->fetch();
		return empty($row) ? NULL : $this->createEmpty()->loadDataFromRow($row);
	}

}
