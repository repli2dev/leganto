<?php
/**
 * @author Jan Papousek
 */
class ShelfSelector implements IShelfSelector
{

	public function findAll() {
		return dibi::dataSource("SELECT * FROM [view_shelf]");
	}

	public function findByUser(UserEntity $user) {
		if (empty($user->getId())) {
			throw new NullPointerException("The user has no ID");
		}
		return $this->findAll()->where("[id_user] = %i", $user->getId());
	}

	public function find($id) {
		$row = dibi::dataSource("SELECT * FROM [view_shelf] where [id_shelf] = %i", $id)->fetch();
		$entity = new AuthorEntity;
		return empty($row) ? NULL : $entity->loadDataFromRow($row);
	}

}
