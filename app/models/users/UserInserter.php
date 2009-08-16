<?php
/**
 * @author Jan Drabek
 */
class UserInserter extends Worker implements IInserter
{

	/* PUBLIC METHODS */
	
	public function insert(UserEntity $entity) {
		if (!$entity->isReadyToInsert()) {
			throw new InvalidArgumentException("The entity is not ready to be inserted.");
		}
		$input = $this->getArrayFromEntity($entity, "Save");
		return $this->getModel()->insert($input);

	}

	/* PROTECTED METHODS */

	protected function createModel() {
		return SimpleTableModel::createTableModel("user");
	}
}