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

}
