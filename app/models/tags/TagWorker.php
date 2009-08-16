<?php
/**
 * @Tag Jan Papousek
 */
class TagWorker extends Worker implements IInserter, IUpdater
{

	/* PUBLIC METHODS */

	public function insert(TagEntity $entity) {
		if (!$entity->isReadyToInsert()) {
			throw new InvalidArgumentException("The entity is not ready to be inserted.");
		}
		// I try to find the tag
		$tag = Leganto::Tags()
			->all()
			->where("[name] = %s", trim($entity->name))
			->where("[id_language] = %i", $entity->languageId)
			->fetch();
		if (empty($tag)) {
			return $tag["id_tag"];
		}
		// It the tag does not exists, insert it
		else {
			$input = $this->getArrayFromEntity($entity, "Save");
			return $this->getModel()->insert($input);
		}


	}

	public function update(TagEntity $entity) {
		if (!$entity->isReadyToUpdate()) {
			throw new InvalidArgumentException("The entity is not ready to be updated.");
		}
		$input = $this->getArrayFromEntity($entity, "Save");
		$this->getModel()->update($entity->getId(), $input);
	}

	/* PROTECTED METHODS */

	protected function createModel() {
		return SimpleTableModel::createTableModel("tag");
	}
}