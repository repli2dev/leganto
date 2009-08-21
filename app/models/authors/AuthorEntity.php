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
class AuthorEntity extends AEntity
{

	const GROUP = "group";

	const PERSON = "person";

	/* PUBLIC ATTRIBUTES */

	/**
	 * @Skip(Save)
	 * @Load(id_author)
	 */
	public $authorId;

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
	 * @Load(last_name)
	 * @Save(last_name)
	 */
	public $lastname;

	/**
	 * @Required
	 */
	public $type;

	/* PRIVATE ATTRIBUTES */
	private $books;

	/* PROTETED METHODS */

	protected function loadIdFromRow(DibiRow $row) {
		$this->setId($row["id_author"]);
	}

}
