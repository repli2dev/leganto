<?php
/**
 * @author Jan Drabek
 */
class TagInserter extends Worker implements IInserter
{

	/* PUBLIC METHODS */
	
	public function insert(TagEntity $entity) {
		if (!$entity->isReadyToInsert()) {
			throw new InvalidArgumentException("The entity is not ready to be inserted.");
		}
		// I try to find the tag
		$tag = Leganto::Tags()->getInserter()->findAll()
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

	/* PROTECTED METHODS */

	protected function createModel() {
		return SimpleTableModel::createTableModel("tag");
	}
}