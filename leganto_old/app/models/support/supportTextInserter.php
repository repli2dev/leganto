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
class SupportTextInserter implements IInserter {
	/* PUBLIC METHODS */

	/**
	 * Insert supportText entity
	 * @param IEntity $entity
	 * @return int supportText id
	 */
	public function insert(IEntity &$entity) {
		return SimpleInserter::createInserter("support_text")->insert($entity);
	}

}