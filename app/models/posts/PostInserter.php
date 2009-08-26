<?php
/**
 * @author Jan Papousek
 */
class PostInserter extends Worker implements IInserter
{

	public function insert(IEntity $entity) {
		if (!$entity->isReadyToInsert()) {
			throw new InvalidArgumentException("The entity is not ready to be inserted.");
		}
		if (!($entity instanceof PostEntity)) {
			throw new InvalidArgumentException("The entity is not the discussion post.");
		}
		// Check if the discussion exists.
		$discussion = SimpleTableModel::createTableModel("discussion")
			->findAll()
			->where("[id_discussable] = %i", $entity->discussionType)
			->where("[id_discussed] = %i", $entity->discussed)
			->fetch();
		// If the discussion does not exists, insert it.
		if (empty($discussion)) {
			// Discussion type
			$discussable = SimpleTableModel::createTableModel("discussable")->find($entity->discussionType);
			// The discussed entity
			$discussed = SimpleTableModel::createTableModel($discussable["table"])->find($entity->discussed);
			// Insert a new discussion
			$entity->discussion = SimpleTableModel::createTableModel("discussion")->insert(array(
				"id_discussable"	=> $entity->discussionType,
				"id_discussed"		=> $entity->discussed,
				"name"				=> $discussed[$discussable["column_name"]],
				"inserted"			=> new DibiVariable("now()", "sql")
			));
		}
		else {
			$entity->discussion = $discussion["id_discussion"];
		}
		// Insert the discussion post
		return SimpleTableModel::createTableModel("post")->insert($this->getArrayFromEntity($entity, "Save"));
	}

}
