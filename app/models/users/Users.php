<?php
/**
 * Model containing registered users. It works with MySQL table 'user'
 * and there is documentation to each column.
 *
 * @author Jan Papousek
 */
class Users extends ATableModel
{

	/**
	 * The name of column which contains autologin ticket.
	 */
	const DATA_AUTOLOGIN_TICKET = "autologin_ticket";

	/**
	 * The e-mail column name.
	 */
	const DATA_EMAIL = "email";

    /**
	 * The identificator column name.
	 */
	const DATA_ID = "id_user";

	/**
	 * The name of column which contains the time when the user was registered.
	 */
	const DATA_INSERTED = "inserted";

	/**
	 * The language id column name.
	 */
	const DATA_LANGUAGE = "id_language";

	/**
	 * The name of column which contains time when the user wass logged last.
	 */
	const DATA_LAST_LOGGED = "last_logged";

	/**
	 * The nickname column name.
	 */
	const DATA_NICKNAME = "nick";

	/**
	 * The password has column name.
	 */
	const DATA_PASSWORD = "password";

	/**
	 * The role column name.
	 */
	const DATA_ROLE = "id_role";

	/**
	 * The name of column which contains user's sex.
	 */
	const DATA_SEX = "sex";

	/**
	 * The type column name.
	 */
	const DATA_TYPE = "type";

	/**
	 * The name of column containing time when the enity was updated last.
	 */
	const DATA_UPDATED = "updated";

	/**
	 * The name of column which contains year of user's birth.
	 */
	const DATA_YEAR_OF_BIRTH = "birth_year";

	/**
	 * The common type.
	 */
	const TYPE_COMMON = "common";

	/**
	 * The root type.
	 */
	const TYPE_ROOT = "root";

	/**
	 * It returns the basic expression used to get data from database.
	 *
	 * @return DibiDataSource
	 * @throws DibiDriverException if there is a problem to work with database.
	 */
	public function findAll() {
		return dibi::dataSource(
			"SELECT *
			 FROM %n", self::getTable(),
			"LEFT JOIN %n USING (%n)", Role::getTable(), Role::DATA_ID
		);
	}

	/**
	 * It returns a name of MySQL table which the model work with.
	 *
	 * @return string
	 */
	public static function getTable() {
		return 'user';
	}

	/**
	 * It returns salted password hash.
	 *
	 * @param string Password in plain text.
	 * @param string E-mail address.
	 *
	 * @return string
	 */
	public static function passwordHash($password, $email) {
		// TODO: Zamyslet se nad hashovaci fci
		return sha1($password);
	}
}

