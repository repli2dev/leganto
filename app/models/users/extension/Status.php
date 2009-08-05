<?php
/**
 * This class provides manipulation with user's status.
 *
 * @author Jan Papousek
 */
class Status extends ATableModel
{

	const DATA_CONTENT = "content";

	const DATA_ID = "id_status";

	const DATA_INSERTED = "inserted";

	const DATA_LANGUAGE = "id_language";

	const DATA_UPDATED = "updated";

	const DATA_USER  = "id_user";

	const VIEW_CONTENT = "content";

	const VIEW_ID = "id_status";

	const VIEW_INSERTED = "inserted";

	const VIEW_LANGUAGE = "id_language";

	const VIEW_UPDATED = "updated";

	const VIEW_USER_ID = "id_user";

	const VIEW_USER_NICKNAME = "user_nickname";

	/**
	 * It returns current status of specified user.
	 * @param int $user User's ID.
	 * @return DibiRow
	 * @throws NullPointerException if the $user is empty.
	 * @throws DibiDriverException if there is a problem to work with database.
	 */
	public function getCurrent($user) {
		if (empty($user)) {
			throw new NullPointerException("user");
		}
		return $this->findAllFromView()
			->where("%n = %i", self::VIEW_USER_ID, $user)
			->orderBy(self::VIEW_INSERTED, "desc")
			->applyLimit(1,0)
			->fetch();
	}

	/**
	 * It returns states with user's info.
	 *
	 * @return DibiDataSource
	 * @throws DibiDriverException if there is a problem to work with database.
	 */
	public function findAllFromView() {
		return dibi::dataSource("SELECT * FROM %n", self::getView());
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
		$input[self::DATA_INSERTED] = new DibiVariable("now()", "sql");
		return parent::insert($input);
	}

	/**
	 * It returns a name of MySQL table which the model work with.
	 *
	 * @return string
	 */
	public static function getTable() {
		return 'status';
	}

	/**
	 * It returns a name of MySQL view which the model work with.
	 *
	 * @return string
	 */
	public static function getView() {
		return 'view_status';
	}

}