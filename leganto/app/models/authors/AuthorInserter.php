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
class AuthorInserter implements IInserter
{

	/* PUBLIC METHODS */
	
	public function insert(IEntity &$entity) {
		if ($entity->getState() != IEntity::STATE_NEW) {
			throw new InvalidArgumentException("The entity can not be inserted because it is not in state [NEW].");
		}
		// I try to find the author
		$source = Leganto::authors()->getSelector()->findAll()
			->where("[type] = %s", $entity->type);
		if ($entity->type == AuthorEntity::GROUP) {
			$source->where("[group_name] = %s", $entity->groupname);
		}
		elseif ($entity->type == AuthorEntity::PERSON) {
			if (!empty($entity->firstname)) {
				$source->where("[first_name] = %s", $entity->firstname);
			}
			if (!empty($entity->lastname)) {
				$source->where("[last_name] = %s", $entity->lastname);
			}
		}
		$author = $source->fetch();
		if (!empty($author)) {
			$authorId = $author["id_author"];
		}
		// It the author does not exists, insert it
		else {
			$input = $entity->getData("Save");
			$authorId = SimpleTableModel::createTableModel("author")->insert($input);
		}
		return $authorId;
		
	}
	
}