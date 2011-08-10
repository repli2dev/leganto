<?php

/**
 * Message updater
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */
namespace Leganto\DB\Message;

use Leganto\ORM\Workers\IUpdater,
    InvalidArgumentException,
    Leganto\DB\Factory,
    Leganto\ORM\Workers\AWorker,
    Leganto\ORM\IEntity,
    Leganto\ORM\SimpleTableModel;

class Updater extends AWorker implements IUpdater {
	/* PUBLIC METHODS */

	function update(IEntity $entity) {
		if ($entity->getState() != IEntity::STATE_MODIFIED) {
			throw new InvalidArgumentException("The entity can not be inserted because it is not in state [MODIFIED].");
		}
		$input = $entity->getData("Save");
		SimpleTableModel::createTableModel("message",$this->connection)->update($entity->getId(), $input);
	}

	public function markRead($items,$offset) {
		$messages = Factory::message()->getSelector()->findAllWithUser(System::user())->applyLimit($items,$offset);
		foreach ($messages as $message) {
			if($message->id_user_to == System::user()->getId()) {
				$this->connection->update("message",array("read" => 1))->where("id_message = %i",$message->id_message)->execute();
			}
		}
	}

}