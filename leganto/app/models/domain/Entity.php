<?php

/**
 * Domain entity
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\Domain;

use Leganto\ORM\AEntity;

/** @Id(translate=id_domain) */
class Entity extends AEntity {

	/** @Translate(id_language) */
	protected $idLanguage;

	/** @Skip(Save) */
	protected $locale;
	protected $uri;
	protected $email;

}
