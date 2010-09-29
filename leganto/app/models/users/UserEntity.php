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

/**
 * @Id(translate=id_user)
 */
class UserEntity extends AEntity {

	const ADMIN	    = "admin";

	const COMMON	    = "common";

	const MODERATOR	    = "moderator";

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

	protected $new_pass_key;
	
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
