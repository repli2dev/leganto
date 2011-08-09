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
namespace Leganto\ACL;

class Action {
	/**
	 * Action when the user edit the entity which is owned by him/her.
	 */
	const EDIT = "edit";

	/**
	 * Action when the user inserts data.
	 */
	const INSERT = "insert";

	/**
	 * Action when the user reads data which is owned by him/her.
	 */
	const READ = "read";
}

