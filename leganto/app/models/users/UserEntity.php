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
	
	/**
	 * @Skip(Form)
	 */
	protected $inserted;

	/**
	 * @Translate(last_logged)
	 * @Skip(Form)
	 */
	protected $lastLogged;

	/**
	 * @Translate(nick)
	 * @Rule(type=filled)
	 */
	protected $nickname;

	protected $password;
	
	/**
	 * @Skip(Form)
	 */
	protected $role;

	protected $sex;

	/**
	 * @Skip(Form)
	 */
	protected $similarity;

	/**
	 * @Skip(Form)
	 */
	protected $type;

	/**
	 * @Skip(Form)
	 */
	protected $updated;
}
