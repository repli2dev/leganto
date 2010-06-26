<?php
/**
 * @author Jan Papousek
 */
class PostInserter implements IInserter
{

	public function insert(IEntity &$entity) {
		if ($entity->getState() != IEntity::STATE_NEW) {
			throw new InvalidArgumentException("The entity can not be inserted because it is not in state [NEW].");
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
			$discussed = SimpleTableModel::createTableModel($discussable["table"])->findAll()->where("%n = %i", $discussable["column_id"], $entity->discussed)->fetch();
			// Insert a new discussion
			$entity->discussion = SimpleTableModel::createTableModel("discussion")->insert(array(
				"id_discussable"	=> $entity->discussionType,
				"id_discussed"		=> $entity->discussed,
				"name"			=> $discussed[$discussable["column_name"]],
				"inserted"		=> new DateTime()
			));
		}
		else {
			$entity->discussion = $discussion["id_discussion"];
		}
		// Insert the discussion post
		$postId = SimpleTableModel::createTableModel("post")->insert($entity->getData("Save"));
		return $postId;
	}

}
