<?php

/**
 * Author entity
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\Author;

use Leganto\ORM\AEntity,
    Leganto\ORM\Object;

/**
 * @Id(translate=id_author)
 */
class Entity extends AEntity {
	const GROUP = "group";

	const PERSON = "person";

	/* PUBLIC ATTRIBUTES */

	/**
	 * @Translate(first_name)
	 */
	protected $firstname;

	/**
	 * @Translate(group_name)
	 */
	protected $groupname;
	protected $inserted;

	/**
	 * @Translate(last_name)
	 */
	protected $lastname;

	/**
	 * @Translate(full_name)
	 */
	protected $fullname;
	protected $type;

	public function equals(Object &$object) {
		if (!($object instanceof Entity)) {
			return FALSE;
		}

		if ($this->type != $object->type) {
			return FALSE;
		}

		if ($this->type == self::GROUP && $this->groupname == $object->groupname) {
			return TRUE;
		}

		if ($this->type == self::PERSON && $this->firstname == $object->firstname && $this->lastname == $object->lastname) {
			return TRUE;
		}
		return FALSE;
	}

}
