<?php

/**
 * Connections entity
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\Connection;

use Leganto\ORM\AEntity;

/**
 * @Id(translate=id_connection)
 */
class Entity extends AEntity {
	
	const FACEBOOK = 'facebook';
	
	const TWITTER = 'twitter';

	/**
	 * @Translate(id_user)
	 * @Rule(type=filled)
	 */
	protected $user;

	/**
	 * @Rule(type=filled)
	 */
	protected $type;

	/**
	 * @Rule(type=filled)
	 */
	protected $token;
	protected $inserted;

}
