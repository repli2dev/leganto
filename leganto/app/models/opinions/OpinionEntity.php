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
