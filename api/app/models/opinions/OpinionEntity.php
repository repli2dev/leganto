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
 * @Id(translate=id_opinion)
 */
class OpinionEntity extends AEntity
{

	/**
	 * @Required
	 * @Save(id_book)
	 * @Load(id_book)
	 */
	public $book;

	/**
	 * @Required
	 */
	public $content;

	/**
	 * @Required
	 */
	public $inserted;

	/**
	 * @Skip(Save)
	 * @Load(locale)
	 */
	public $language;

	/**
	 * @Required
	 * @Save(id_language)
	 * @Load(id_language)
	 */
	public $languageId;

	/**
	 * @Required
	 */
	public $rating;

	/**
	 * @Load(id_user)
	 * @Save(id_user)
	 * @Required
	 */
	public $userId;

	/**
	 * @Load(user_nick)
	 * @Skip(Save)
	 */
	public $userName;

}
