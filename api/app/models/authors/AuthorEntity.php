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
 * @Id(translate=id_author)
 */
class AuthorEntity extends AEntity
{

	const GROUP = "group";

	const PERSON = "person";

	/* PUBLIC ATTRIBUTES */

	/**
	 * @Load(first_name)
	 * @Save(first_name)
	 */
	public $firstname;

	/**
	 * @Load(group_name)
	 * @Save(group_name)
	 */
	public $groupname;

	/**
	 * @Required
	 */
	public $inserted;

	/**
	 * @Load(last_name)
	 * @Save(last_name)
	 */
	public $lastname;

	/**
	 * @Required
	 */
	public $type;
	
	/* PUBLIC METHODS */
	
	public function equals(EskymoObject &$object){
		if(!($object instanceof AuthorEntity)){
			return FALSE;
		}
				
		if($this->type != $object->type){
			return FALSE;
		}
		
		if($this->type == self::GROUP && $this->groupname == $object->groupname){
			return TRUE;
		}
		
		if($this->type == self::PERSON && $this->firstname == $object->firstname && $this->lastname == $object->lastname){
			return TRUE;
		}
		return FALSE;
	}
}
