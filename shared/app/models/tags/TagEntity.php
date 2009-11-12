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
 * @Id(translate=id_tag)
 */
class TagEntity extends AEntity
{

	/* PUBLIC ATTRIBUTES */

	/**
	 * @Translate(id_language)
	 */
	protected $languageId;

	protected $name;
	
	/* PUBLIC METHODS */
	
	public function equals(EskymoObject &$object){
		if(!($object instanceof TagEntity)){
			return FALSE;
		}
		return ($this->name == $object->name && $this->languageId == $object->languageId);
	}

}
