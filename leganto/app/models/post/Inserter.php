<?php

/**
 * Post inserter
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\Post;

use Leganto\ORM\Workers\IInserter,
    Leganto\ORM\Workers\AWorker,
    Leganto\ORM\IEntity,
    Leganto\ORM\SimpleTableModel,
    InvalidArgumentException;

class Inserter extends AWorker implements IInserter {

	public function insert(IEntity &$entity) {
		if ($entity->getState() != IEntity::STATE_NEW) {
			throw new InvalidArgumentException("The entity can not be inserted because it is not in state [NEW].");
		}
		if (!($entity instanceof PostEntity)) {
			throw new InvalidArgumentException("The entity is not the discussion post.");
		}
		// Check if the discussion exists.
		$discussion = SimpleTableModel::createTableModel("discussion", $this->connection)
			->findAll()
			->where("[id_discussable] = %i", $entity->discussionType)
			->where("[id_discussed] = %i", $entity->discussed)
			->fetch();
		// If the discussion does not exists, insert it.
		if (empty($discussion)) {
			// Discussion type
			$discussable = SimpleTableModel::createTableModel("discussable", $this->connection)->find($entity->discussionType);
			// The discussed entity
			$discussed = SimpleTableModel::createTableModel($discussable["table"], $this->connection)->findAll()->where("%n = %i", $discussable["column_id"], $entity->discussed)->fetch();
			// Insert a new discussion
			$toInsert = array(
			    "id_discussable" => $entity->discussionType,
			    "id_discussed" => $entity->discussed,
			    "name" => $discussed[$discussable["column_name"]],
			    "inserted" => new DateTime()
			);
			if (!empty($discussable["column_subname"])) {
				$toInsert["subname"] = $discussed[$discussable["column_subname"]];
			}
			$entity->discussion = SimpleTableModel::createTableModel("discussion", $this->connection)->insert($toInsert);
		} else {
			$entity->discussion = $discussion["id_discussion"];
		}
		// Insert the discussion post
		$postId = SimpleTableModel::createTableModel("post", $this->connection)->insert($entity->getData("Save"));
		return $postId;
	}

}
