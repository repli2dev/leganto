<?php

/**
 * Support text entity
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\SupportText;

use Leganto\ORM\AEntity;

/**
 * @Id(translate=id_support_text)
 */
class Entity extends AEntity {

	/**
	 * @Translate(id_support_category)
	 */
	protected $id_support_category;
	protected $name;
	protected $text;
	protected $updated;
	protected $weight;

}
