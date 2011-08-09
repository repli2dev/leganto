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
namespace Leganto\DB\SupportCategory;
use Leganto\ORM\AEntity;

/**
 * @Id(translate=id_support_category)
 */
class Entity extends AEntity {

	/**
	 * @Translate(id_language)
	 */
	protected $languageId;
	protected $name;
	protected $weight;
	protected $updated;

}
