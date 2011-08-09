<?php

/**
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */

class MessageUpdater implements IUpdater {
	/* PUBLIC METHODS */

	function update(IEntity $entity) {
		if ($entity->getState() != IEntity::STATE_MODIFIED) {
			throw new InvalidArgumentException("The entity can not be inserted because it is not in state [MODIFIED].");
		}
		$input = $entity->getData("Save");
		SimpleTableModel::createTableModel("message")->update($entity->getId(), $input);
	}

	public function markRead($items,$offset) {
		$messages = Leganto::messages()->getSelector()->findAllWithUser(System::user())->applyLimit($items,$offset);
		foreach ($messages as $message) {
			if($message->id_user_to == System::user()->getId()) {
				dibi::update("message",array("read" => 1))->where("id_message = %i",$message->id_message)->execute();
			}
		}
	}

}