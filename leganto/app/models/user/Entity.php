<?php

/**
 * user entity
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\User;

use Leganto\ORM\AEntity;

/**
 * @Id(translate=id_user)
 */
class Entity extends AEntity {
	const ADMIN = "admin";

	const COMMON = "common";

	const MODERATOR = "moderator";

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
	 * @Translate(id_language)
	 */
	protected $idLanguage;

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
	 * @Translate(new_pass_key)
	 */
	protected $newPassKey;

	/**
	 * @Translate(new_pass_time)
	 */
	protected $newPassTime;

	/**
	 * @Translate(num_opinions)
	 * @Skip(Save)
	 */
	protected $numberOfOpinions;
	protected $about;
	protected $role;
	protected $sex;

	/**
	 * @Skip(Save)
	 */
	protected $similarity;

	/**
	 * @Skip(Form)
	 */
	protected $updated;

}
