<?php
/**
 * @author Jan Papousek
 */
class AuthorWorker extends Worker implements IInserter, IUpdater
{

	/* PUBLIC METHODS */

	public function insert(AuthorEntity $entity) {
		if (!$entity->isReadyToInsert()) {
			throw new InvalidArgumentException("The entity is not ready to be inserted.");
		}
		// I try to find the author
		$source = Leganto::authors()
			->all()
			->where("[type] = %s", $entity->type);
		if ($entity->type == AuthorEntity::GROUP) {
			$source->where("[groupname] = %s", $entity->groupname);
		}
		elseif ($entity->type == AuthorEntity::PERSON) {
			if (!empty($entity->firstname)) {
				$source->where("[firstname] = %s", $entity->firstname);
			}
			if (!empty($entity->lastname)) {
				$source->where("[lastname] = %s", $entity->lastname);
			}
		}
		$author = $source->fetch();
		if (empty($author)) {
			return $author["id_author"];
		}
		// It the author does not exists, insert it
		else {
			$input = $this->getArrayFromEntity($entity, "Save");
			return $this->getModel()->insert($input);
		}


	}

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