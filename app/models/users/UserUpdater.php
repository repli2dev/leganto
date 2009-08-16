<?php
/**
 * @author Jan Drabek
 */
class UserUpdater extends Worker implements IUpdater
{

	/* PUBLIC METHODS */
	
	public function update(UserEntity $entity) {
		if (!$entity->isReadyToUpdate()) {
			throw new InvalidArgumentException("The entity is not ready to be updated.");
		}
		$input = $this->getArrayFromEntity($entity, "Save");
		return $this->getModel()->update($entity->getId(), $input);
	}

	/* PROTECTED METHODS */

	protected function createModel() {
		return SimpleTableModel::createTableModel("user");
	}
}