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
class AuthorInserter implements IInserter {
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
		} elseif ($entity->type == AuthorEntity::PERSON) {
			$firstname = $entity->firstname;
			$lastname = $entity->lastname;
			// TODO: Check for non deterministic behaviour (add else branches for first_name = "" etc?)
			if (!empty($firstname)) {
				$source->where("[first_name] = %s", $firstname);
			}
			if (!empty($lastname)) {
				$source->where("[last_name] = %s", $lastname);
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