<?php
namespace Leganto\DB\Opinion;
use Leganto\ORM\Workers\IInserter,
	Leganto\DB\Factory,
	Leganto\ORM\SimpleEntityFactory,
	\dibi as dibi;

class Inserter implements IInserter {

	public function insert(IEntity &$entity) {
		if ($entity->getState() != IEntity::STATE_NEW) {
			throw new InvalidArgumentException("The entity can not be inserted because it is not in state [NEW].");
		}
		$opinionId = SimpleTableModel::createTableModel("opinion")->insert($entity->getData("Save"));
		Factory::shelves()->getUpdater()->removeReadBookFromShelves($entity->bookTitleId, $entity->userId);
		return $opinionId;
	}

}
