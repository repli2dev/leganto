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

/** @Id(translate=id_domain) */
class DomainEntity extends AEntity {

	/** @Translate(id_language) */
	protected $idLanguage;
	/** @Skip(Save) */
	protected $locale;
	protected $uri;
	protected $email;
}
