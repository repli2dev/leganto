<?php
/**
 * @author Jan Papousek
 */
class ShelfInserter extends Worker implements IInserter
{

	/* PUBLIC METHODS */

	public function insert(ShelfEntity $entity) {
		if (!$entity->isReadyToInsert()) {
			throw new InvalidArgumentException("The entity is not ready to be inserted.");
		}
		return SimpleTableModel::createTableModel("shelf")
			->insert($this->getArrayFromEntity($entity, "Save"));
	}

}