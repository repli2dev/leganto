<?php
/**
 * @author Jan Papousek
 */
class ShelfEntity extends AEntity
{

	/**
	 * @Required
	 */
	public $inserted;

	/**
	 * @Required
	 */
	public $name;

	/**
	 * @Skip(Save)
	 * @Load(number_of_books)
	 */
	public $numberOfBooks;

	/**
	 * @Required
	 */
	public $type;

	/**
	 * @Required
	 * @Load(id_user)
	 * @Save(id_user)
	 */
	public $user;

	/**
	 * @Skip(Save)
	 * @Load(user_nick)
	 */
	public $userName;

	/* PROTECTED METHODS */

	protected function loadId(array $source) {
		if (isset($source["id_shelf"])) {
			$this->setId($source["id_shelf"]);
		}
	}

}
