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
class UserSelector extends Worker implements ISelector
{

	/* PUBLIC METHODS */
	
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [view_user]");
	}
	
	/** @return UserEntity */
	public function findOne($id) {
		$row = dibi::dataSource("SELECT * FROM [view_user] WHERE [id_user] = %i", $id)->fetch();
		$entity = new UserEntity;
		return empty($row) ? NULL : $entity->loadDataFromRow($row);
	}
}