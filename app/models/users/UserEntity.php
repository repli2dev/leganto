<?php
/**
 * @author Jan Papousek
 */
class UserEntity extends AEntity
{
	
	/**
	 * @Load(birth_year)
	 */
	public $birthyear;

	/**
	 * @Required
	 */
	public $email;

	/**
	 * @Required
	 */
	public $inserted;

	/**
	 * @Load(last_logged)
	 * @Save(last_logged)
	 */
	public $lastLogged;

	/**
	 * @Load(nick)
	 * @Required
	 */
	public $nickname;

	/**
	 * @Requiered
	 */
	public $password;

	public $role;

	public $sex;

	/** @Required */
	public $type;

	public $updated;

}
