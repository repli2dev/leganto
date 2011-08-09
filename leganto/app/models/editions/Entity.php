<?php

/**
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
namespace Leganto\DB\Edition;
use Leganto\ORM\AEntity;

/** @Id(translate=id_edition) */
class Entity extends AEntity {

	/** @Translate(id_book_title) */
	protected $idBookTitle;
	protected $inserted;
	protected $isbn10;
	protected $isbn13;
	protected $pages;
	protected $published;
	protected $image;

}
