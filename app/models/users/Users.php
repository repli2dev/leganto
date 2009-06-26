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
		/// Loading data from database
		$rows = $this->get()->where(
			"%n = %s",
			self::DATA_EMAIL,
			$credentials[IAuthenticator::USERNAME]
		);
		// Does the user exist?
		if ($rows->count() == 0) {
			throw new AuthenticationException(
				"Username not found.",
				AuthenticationException::IDENTITY_NOT_FOUND
			);
		}
		$user = $rows->fetch();
		// Is there a valid password
		$password = self::passwordHash(
			$credentials[IAuthenticator::PASSWORD],
			$user[self::DATA_EMAIL]
		);
		if ($user[self::DATA_PASSWORD] !== $password) {
			throw new AuthenticationException(
				"Inavalid password.",
				AuthenticationException::INVALID_CREDENTIAL
			);
		}
		// Last logged
		$this->update($id, array(self::DATA_LAST_LOGGED => new DibiVariable("NULL","sql")));
		// Logged user
		unset($user[self::DATA_PASSWORD]);
		return new Identity(
			$user[self::DATA_NICKNAME],
			$user[Role::DATA_NAME],
			$user
		);
	}

	/**
	 * It returns the basic expression used to get data from database.
	 *
	 * @return DibiDataSource
	 * @throws DibiDriverException if there is a problem to work with database.
	 */
	public function get() {
		return dibi::dataSource(
			"SELECT *
			 FROM %n", self::getTable(),
			"LEFT JOIN %n ON %n", Role::getTable(), self::DATA_ROLE
		);
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
	 * It inserts an entity to the database.
	 *
	 * @param array|mixed $input The input data, keys are names of the columns
	 *		and values are content.
	 * @return int Identificator of the new entity in database
	 *		or '-1' if the entity has already existed.
	 * @return InvalidArgumentException if the $input is not an array.
	 * @throws NullPointerException if the input is empty or does not contain
	 *		all necessary columns.
	 * @throws DataNotFoundException if there is a foreign key on not existing entity.
	 * @throws DibiDriverException if there is a problem to work with database.
	 */
	public function insert(array $input) {
		if (empty($input[self::DATA_PASSWORD])) {
			throw new NullPointerException("input[" . self::DATA_PASSWORD . "]");
		}
		if (empty($input[self::DATA_EMAIL])) {
			throw new NullPointerException("input[" . self::DATA_EMAIL . "]");
		}
		$input[self::DATA_PASSWORD] = self::passwordHash(
			$input[self::DATA_PASSWORD],
			$input[self::DATA_EMAIL]
		);
		return parent::insert($input);
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

	/**
	 * It updates en entity in the database.
	 *
	 * @param int $id The identificator of the entity.
	 * @param array|mixed $input	The new data describig entity,
	 *		array keys are columns name of the table in database
	 *		and values are the content.
	 * @return boolean It return TRUE if the entity was changed,
	 *		otherwise FALSE.
	 * @throws InvalidArgumentException if the $input is not an array.
	 * @throws NullPointerException if $id is empty.
	 * @throws DataNotFoundException if the entity does not exist
	 *		or there is the foreign key on the intity which does not exist.
	 * @throws DibiDriverException if there is a problem to work with database.
	 */
	public function update($id, $input) {
		if (empty($id)) {
			throw new NullPointerException("id");
		}
		$rows = $this->get()->where("%n = %i", self::DATA_ID, $id);
		if ($rows->count() == 0) {
			throw new DataNotFoundException("id");
		}
		$user = $rows->fetch();
		if (isset($input[self::DATA_PASSWORD])) {
			$input[self::DATA_PASSWORD] = self::passwordHash(
				$input[self::DATA_PASSWORD],
				$user[self::DATA_EMAIL]
			);
		}
		return parent::update($id, $input);
	}
}

