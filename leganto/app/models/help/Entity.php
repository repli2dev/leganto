<?php

/**
 * Help entity
 * 
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\Help;

use Leganto\ORM\AEntity;

/**
 * @Id(translate=id_help)
 */
class Entity extends AEntity {
	const BOOK = "book";

	const AUTHOR = "author";

	const USER = "user";

	const OTHER = "other";

	protected $category;
	protected $text;
	protected $image;

}
