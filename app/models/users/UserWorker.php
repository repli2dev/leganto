<?php
/**
 * @author Jan Papousek
 */
class UserWorker extends Worker implements IInserter, IUpdater
{

	/* PUBLIC METHODS */

	public function insert(UserEntity $entity) {
		if (!$entity->isReadyToInsert()) {
			throw new InvalidArgumentException("The entity is not ready to be inserted.");
		}
		$input = $this->getArrayFromEntity($entity, "Save");
		return $this->getModel()->insert($input);

	}

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