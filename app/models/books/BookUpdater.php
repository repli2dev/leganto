<?php
/**
 * @author Jan Drabek
 */
class BookUpdater extends Worker implements IUpdater
{

	/* PUBLIC METHODS */
	
	public function update(BookEntity $entity) {
		if (!$entity->isReadyToUpdate()) {
			throw new InvalidArgumentException("The entity is not ready to be updated.");
		}
		$input = $this->getArrayFromEntity($entity, "Save");
		SimpleTableModel::createTableModel("book_title")->update($entity->getId(), $input);
	}

	/* PROTECTED METHODS */

	protected function createModel() {
		throw new NotSupportedException();
	}
}