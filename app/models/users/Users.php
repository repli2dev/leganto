<?php
/**
 * Model containing registered users. It works with MySQL table 'user'
 * and there is documentation to each column.
 *
 * @author Jan Papousek
 */
class Users extends ATableModel implements IAuthenticator
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
	const DATA_ROLE = "role";

	/**
	 * The type column name.
	 */
	const DATA_TYPE = "type";

	/**
	 * The name of column containing time when the enity was updated last.
	 */
	const DATA_UPDATED = "updated";

	/**
	 * The common type.
	 */
	const TYPE_COMMON = "common";

	/**
	 * The root type.
	 */
	const TYPE_ROOT = "root";

	public function authenticate(array $credentials) {
		throw new NotSupportedException();
	}

	/**
	 * It returns a name of MySQL table which the model work with.
	 *
	 * @return string
	 */
	public static function getTable() {
		$tables = Environment::getConfig('tables');
		return (!empty($tables->user) ? $tables->user : 'user');
	}

	/**
	 * It returns salted password hash.
	 *
	 * @param string Password in plain text.
	 * @param string E-mail address.
	 *
	 * @return <type>
	 */
	protected static function passwordHash($password, $email) {
		// TODO: Zamyslet se nad hashovaci fci
		throw new NotSupportedException();
	}

	protected function requiredColumns() {
		return array(
			self::DATA_ID,
			self::DATA_EMAIL,
			self::DATA_LANGUAGE,
			self::DATA_NICKNAME,
			self::DATA_PASSWORD,
			self::DATA_TYPE
		);
	}

	protected function tableName() {
		return self::getTable();
	}
}

