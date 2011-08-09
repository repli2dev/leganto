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
namespace Leganto\DB\Language;
use Leganto\ORM\AEntity;

/**
 * @Id(translate=id_language)
 */
class Entity extends AEntity {

	protected $name;
	protected $locale;
	protected $google;
	/** @Translate(z39_50) */
	protected $z3950;
}
