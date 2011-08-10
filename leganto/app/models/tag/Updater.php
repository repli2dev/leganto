<?php

/**
 * Tag updater
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */

namespace Leganto\DB\Tag;

use Leganto\ORM\Workers\IInserter,
    Leganto\ORM\IEntity,
    Leganto\ORM\SimpleTableModel,
    Leganto\ORM\Workers\AWorker,
    InvalidArgumentException;

class Updater extends AWorker implements IUpdater {

	/**
	 * Merge two tags (transactionaly)
	 * @param Entity $superior
	 * @param Entity $inferior
	 */
	public function merge(Entity $superior, Entity $inferior) {
		$this->connection->begin();
		$conflictBooks = $this->connection->query("SELECT [id_book] FROM [tagged] WHERE [id_tag] = %i", $superior->getId())->fetchPairs("id_book", "id_book");
		if (!empty($conflictBooks)) {
			$this->connection->delete("tagged")->where("[id_tag] = %i", $inferior->getId(), " AND [id_book] IN %l", $conflictBooks)->execute();
		}
		$this->connection->update("tagged", array("id_tag" => $superior->getId()))->where("[id_tag] = %i", $inferior->getId())->execute();
		$this->connection->delete("tag")->where("[id_tag] = %i", $inferior->getId())->execute();
		$this->connection->commit();
	}

	/**
	 * Persist given (changed) entity
	 * @param IEntity $entity
	 */
	public function update(IEntity $entity) {
		if ($entity->getState() != IEntity::STATE_MODIFIED) {
			throw new InvalidArgumentException("The entity can not be inserted because it is not in state [MODIFIED].");
		}
		SimpleTableModel::createTableModel("tag", $this->connection)->update($entity->getId(), $entity->getData("Save"));
	}

}