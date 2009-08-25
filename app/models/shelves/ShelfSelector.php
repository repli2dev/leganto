<?php
/**
 * @author Jan Papousek
 */
class ShelfSelector implements ISelector
{

	public function findAll() {
		return dibi::dataSource("SELECT * FROM [view_shelf]");
	}

	public function findByUser(UserEntity $user) {
		if ($user->getId() == NULL) {
			throw new NullPointerException("The user has no ID");
		}
		return $this->findAll()->where("[id_user] = %i", $user->getId());
	}

	public function find($id) {
		return Leganto::shelves()
			->fetchAndCreate(
				dibi::dataSource("SELECT * FROM [view_shelf] where [id_shelf] = %i", $id)
			);
	}

}
