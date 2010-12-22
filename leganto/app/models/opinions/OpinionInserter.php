<?php

class OpinionInserter extends IInserter {

    public function insert(IEntity &$entity) {
        if ($entity->getState() != IEntity::STATE_NEW) {
            throw new InvalidArgumentException("The entity can not be inserted because it is not in state [NEW].");
        }
        $opinionId = SimpleTableModel::createTableModel("opinion")->insert($entity->getData("Save"));
		Leganto::shelves()->getUpdater()->removeReadBookFromShelves($entity->bookTitleId, $entity->userId);
    }

}
