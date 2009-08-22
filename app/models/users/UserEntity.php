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
class UserEntity extends AEntity
{
	
	/**
	 * @Load(birth_year)
	 */
	public $birthyear;

	/**
	 * @Required
	 */
	public $email;

	/**
	 * @Required
	 */
	public $inserted;

	/**
	 * @Load(last_logged)
	 * @Save(last_logged)
	 */
	public $lastLogged;

	/**
	 * @Load(nick)
	 * @Required
	 */
	public $nickname;

	/**
	 * @Requiered
	 */
	public $password;

	public $role;

	public $sex;

	/** @Required */
	public $type;

	public $updated;

	protected function loadIdFromRow(DibiRow $row) {
		$this->setId($row["id_user"]);
	}
}
