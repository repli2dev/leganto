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

/**
 * @Id(translate=id_user)
 */
class UserEntity extends AEntity
{
	
	/**
	 * @Translate(birth_year)
	 */
	protected $birthyear;

	protected $email;

	protected $inserted;

	/**
	 * @Translate(last_logged)
	 */
	protected $lastLogged;

	/**
	 * @Translate(nick)
	 */
	protected $nickname;

	protected $password;

	protected $role;

	protected $sex;

	/**
	 * @Translate(Save)
	 */
	protected $similarity;

	protected $type;

	protected $updated;
}
