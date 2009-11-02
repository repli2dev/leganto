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
 * @Id(translate=id_shelf)
 */
class ShelfEntity extends AEntity
{

	/**
	 * @Required
	 */
	public $inserted;

	/**
	 * @Required
	 */
	public $name;

	/**
	 * @Skip(Save)
	 * @Load(number_of_books)
	 */
	public $numberOfBooks;

	/**
	 * @Required
	 */
	public $type;

	/**
	 * @Required
	 * @Load(id_user)
	 * @Save(id_user)
	 */
	public $user;

	/**
	 * @Skip(Save)
	 * @Load(user_nick)
	 */
	public $userName;

}
