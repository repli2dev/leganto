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
 * @Id(translate=id_book_title)
 */
class BookEntity extends AEntity
{

	/**
	 * @Translate(id_book)
	 */
	protected $bookNode;

	/**
	 * @Skip(Save)
	 * @Translate(locale)
	 */
	protected $language;

	/**
	 * @Translate(id_language)
	 */
	protected $languageId;

	/**
	 * @Required
	 */
	protected $inserted;

	/**
	 * @Skip(Save)
	 * @Translate(number_of_opinions)
	 */
	protected $numberOfOpinions;

	/**
	 * @Skip(Save)
	 * @Translate(number_of_readers)
	 */
	protected $numberOfReaders;

	/**
	 * @Skip(Save)
	 */
	protected $rating;

	/**
	 * @Skip(Save)
	 */
	protected $similarity;

	protected $subtitle;

	protected $title;

	protected $updated;

}
