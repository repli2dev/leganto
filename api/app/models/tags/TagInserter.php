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
class TagInserter extends Worker implements IInserter
{

	/* PUBLIC METHODS */
	
	public function insert(IEntity &$entity) {
		if (!$entity->isReadyToInsert()) {
			throw new InvalidArgumentException("The entity is not ready to be inserted.");
		}
		// I try to find the tag
		$tag = Leganto::Tags()->getSelector()->findAll()
			->where("[name] = %s", trim($entity->name))
			->where("[id_language] = %i", $entity->languageId)
			->fetch();
		if (!empty($tag)) {
			$tagId = $tag["id_tag"];
		}
		// It the tag does not exists, insert it
		else {
			$input = $this->getArrayFromEntity($entity, "Save");
			$tagId = SimpleTableModel::createTableModel("tag")->insert($input);
		}
		if(!empty($tagId) && $tagId != -1) {
			$entity->setId($tagId);
		}
		return $tagId;


	}
	
}