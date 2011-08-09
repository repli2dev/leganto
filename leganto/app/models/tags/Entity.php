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
namespace Leganto\DB\Tag;
use Leganto\ORM\AEntity,
 Leganto\ORM\Object;

/**
 * @Id(translate=id_tag)
 */
class Entity extends AEntity {
	/* PUBLIC ATTRIBUTES */

	/**
	 * @Translate(id_language)
	 */
	protected $languageId;
	protected $name;

	/* PUBLIC METHODS */

	public function equals(Object &$object) {
		if (!($object instanceof Entity)) {
			return FALSE;
		}
		return ($this->name == $object->name && $this->languageId == $object->languageId);
	}

}
