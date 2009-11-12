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
 * @Id(translate=id_help)
 */
class HelpEntity extends AEntity
{

	const BOOK = "book";

	const AUTHOR = "author";

	const USER = "user";

	const OTHER = "other";

	/* PUBLIC ATTRIBUTES */

	/**
	 * @Required
	 */
	public $category;

	/**
	 * @Required
	 */
	public $text;

	public $image;

}
