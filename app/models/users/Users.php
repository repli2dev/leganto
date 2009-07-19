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
	 * @return InvalidArgumentException if the $input[email] is not valid.
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
		$validator = new EmailValidator();
		if (!$validator->isValid($input[self::DATA_EMAIL])) {
			throw new InvalidArgumentException("input[email]");
		}
		$input[self::DATA_PASSWORD] = self::passwordHash(
			$input[self::DATA_PASSWORD],
			$input[self::DATA_EMAIL]
		);
		$input[self::DATA_INSERTED] = new DibiVariable("now()", "sql");
		return parent::insert($input);
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
	public function update($id, array $input) {
		if (empty($id)) {
			throw new NullPointerException("id");
		}
		$rows = $this->findAll()->where("%n = %i", self::DATA_ID, $id);
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

