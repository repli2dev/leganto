<?php

/**
 * Opinion inserter
 * 
 * @author Jan Papousek
 * @author Jan Drabek
 */

namespace Leganto\DB\Opinion;

use Leganto\ORM\Workers\IInserter,
    Leganto\DB\Factory,
    Leganto\ORM\SimpleEntityFactory,
    InvalidArgumentException,
    Leganto\ORM\IEntity,
    Leganto\ORM\Workers\AWorker;

class Inserter extends AWorker implements IInserter {

	public function insert(IEntity &$entity) {
		if ($entity->getState() != IEntity::STATE_NEW) {
			throw new InvalidArgumentException("The entity can not be inserted because it is not in state [NEW].");
		}
		$opinionId = SimpleTableModel::createTableModel("opinion",$this->connection)->insert($entity->getData("Save"));
		Factory::shelf()->getUpdater()->removeReadBookFromShelves($entity->bookTitleId, $entity->userId);
		return $opinionId;
	}

}
