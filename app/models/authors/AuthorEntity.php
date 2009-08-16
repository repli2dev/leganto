<?php
/**
 * @author Jan Papousek
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
