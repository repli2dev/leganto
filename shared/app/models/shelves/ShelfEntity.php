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

	protected $inserted;

	protected $name;

	/**
	 * @Skip(Save)
	 * @Translate(number_of_books)
	 */
	protected $numberOfBooks;

	protected $type;

	/**
	 * @Translate(id_user)
	 */
	protected $user;

	/**
	 * @Skip(Save)
	 * @Translate(user_nick)
	 */
	protected $userName;

}
