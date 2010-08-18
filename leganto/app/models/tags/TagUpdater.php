<?php
/**
 * The source file is subject to the license located on web
 * "http://code.google.com/p/preader/".
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (repli2dev@gmail.com)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 */

/**
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$Id$
 */
class TagUpdater implements IUpdater
{

	/* PUBLIC METHODS */

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

	public function update(IEntity $entity) {
		if ($entity->getState() != IEntity::STATE_MODIFIED) {
			throw new InvalidArgumentException("The entity can not be inserted because it is not in state [MODIFIED].");
		}
		SimpleTableModel::createTableModel("tag")->update($entity->getId(), $entity->getData("Save"));
	}

}