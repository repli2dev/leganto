<?php
/**
 * @author Jan Papousek
 */
class ShelfUpdater extends Worker implements IUpdater
{

	public function update(ShelfEntity $entit) {
		if (!$entity->isReadyToUpdate()) {
			throw new InvalidArgumentException("The entity is not ready to be updated.");
		}
		return SimpleTableModel::createTableModel("shelf")
			->update($this->getArrayFromEntity($entity, "Save"));
	}

}
