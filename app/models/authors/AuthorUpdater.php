<?php
/**
 * @author Jan Drabek
 */
class AuthorUpdater extends Worker implements IUpdater
{

	/* PUBLIC METHODS */
	
	public function update(AuthorEntity $entity) {
		if (!$entity->isReadyToUpdate()) {
			throw new InvalidArgumentException("The entity is not ready to be updated.");
		}
		$input = $this->getArrayFromEntity($entity, "Save");
		$this->getModel()->update($entity->getId(), $input);
	}

	/* PROTECTED METHODS */

	protected function createModel() {
		return SimpleTableModel::createTableModel("author");
	}
}