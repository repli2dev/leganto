<?php

/**
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
class TagUpdater implements IUpdater {
	/* PUBLIC METHODS */

	/**
	 * Merge two tags (transactionaly)
	 * @param TagEntity $superior
	 * @param TagEntity $inferior
	 */
	public function merge(TagEntity $superior, TagEntity $inferior) {
		dibi::begin();
		$conflictBooks = dibi::query("SELECT [id_book] FROM [tagged] WHERE [id_tag] = %i", $superior->getId())->fetchPairs("id_book", "id_book");
		if (!empty($conflictBooks)) {
			dibi::delete("tagged")->where("[id_tag] = %i", $inferior->getId(), " AND [id_book] IN %l", $conflictBooks)->execute();
		}
		dibi::update("tagged", array("id_tag" => $superior->getId()))->where("[id_tag] = %i", $inferior->getId())->execute();
		dibi::delete("tag")->where("[id_tag] = %i", $inferior->getId())->execute();
		dibi::commit();
	}

	/**
	 * Persist given (changed) entity
	 * @param IEntity $entity
	 */
	public function update(IEntity $entity) {
		if ($entity->getState() != IEntity::STATE_MODIFIED) {
			throw new InvalidArgumentException("The entity can not be inserted because it is not in state [MODIFIED].");
		}
		SimpleTableModel::createTableModel("tag")->update($entity->getId(), $entity->getData("Save"));
	}

}