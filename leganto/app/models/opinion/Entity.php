<?php

/**
 * Opinion entity
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\Opinion;

use Leganto\ORM\AEntity;

/**
 * @Id(translate=id_opinion)
 */
class Entity extends AEntity {

	/**
	 * @Translate(id_book_title)
	 */
	protected $bookTitleId;

	/**
	 * @Skip(Save)
	 * @Translate(book_title)
	 */
	protected $bookTitle;
	protected $content;
	protected $inserted;

	/**
	 * @Translate(Save)
	 */
	protected $language;

	/**
	 * @Translate(id_language)
	 */
	protected $languageId;
	protected $rating;

	/**
	 * @Translate(id_user)
	 */
	protected $userId;

	/**
	 * @Translate(user_nick)
	 */
	protected $userName;

}
