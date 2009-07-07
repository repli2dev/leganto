<?php
/*
 * Reader's book
 *
 * @copyright   Copyright (c) 2004, 2009 Jan Papousek, Jan Drabek
 * @link        http://code.google.com/p/preader/
 * @category    Reader
 * @package     Reader\Base\Modules
 * @version     2009-07-04
 */

/*namespace Reader\Messages;*/

/**
 * The model providing the manipulation with messages.
 *
 * @author      Jan Papousek
 * @version     2009-07-04
 * @package     Reader\Messages
 */
class Message extends ATableModel
{

	const DATA_ID = "id_message";

	const DATA_USER_FROM = "id_user_from";

	const DATA_USER_TO = "id_user_to";

	const DATA_DESTROYED_FROM = "from_destroyed";

	const DATA_DESTROYED_TO = "to_destroyed";

	const DATA_IS_READ = "is_read";

	const DATA_SUBJECT = "subject";

	const DATA_CONTENT = "content";

	const DATA_INSERTED = "inserted";

	const IS_READ_YES = 1;

	const IS_READ_NO = 0;

	const VIEW_ID = "id_message";

	const VIEW_SUBJECT  = "subject";

	const VIEW_CONTENT = "content";

	const VIEW_INSERTED = "inserted";

	const VIEW_TO_DESTROYED = "to_destroyed";

	const VIEW_TO_ID	= "to_id_user";

	const VIEW_TO_NICKNAME = "to_nick";

	const VIEW_FROM_DESTROYED = "from_destroyed";

	const VIEW_FROM_ID = "from_id_user";

	const VIEW_FROM_NICKNAME = "from_nick";

	/**
	 * It returns the basic expression used to get data from database.
	 *
	 * @return DibiDataSource
	 * @throws DibiDriverException if there is a problem to work with database.
	 */
	public function get() {
		return dibi::dataSource("SELECT * FROM %n", self::getView());
	}

	/**
	 * It returns messages which are not read.
	 *
	 * @param int $user User's ID.
	 * @return DibiDataSource
	 * @throws NullPointerException if the $user is empty.
	 * @throws DibiDriverException if there is a problem to work with database.
	 */
	public function getNotRead($user) {
		if (empty($user)) {
			throw new NullPointerException("user");
		}
		return $this->get()->where(
			"%n = %i", self::DATA_USER_TO, $user,
			" %and %n = %i", self::DATA_IS_READ, self::IS_READ_NO
		);
	}

	/**
	 * It returns a name of MySQL table which the model work with.
	 *
	 * @return string
	 */
	public static function getTable() {
		$tables = Environment::getConfig('tables');
		return (!empty($tables->message) ? $tables->message : 'message');
	}

	/**
	 * It retursn a name of MySQL view which the model work with.
	 *
	 * @return string
	 */
	public static function getView() {
		$views = Environment::getConfig('views');
		return (!empty($tables->message) ? $tables->message : 'view_message');
	}

	protected function tableName() {
		return self::getTable();
	}

	/**
	 * It marks the user's message as deleted. If the both users marked the message,
	 * the message will be really deleted.
	 *
	 * @param int $message Message ID.
	 * @param int $user ID of user, who sent the message or whom the message was sent.
	 * @throws NullPointerException if the $message or $user is empty.
	 * @throws DataNotFoundException if the message does not exist.
	 * @throws DibiDriverException if there is a problem to work with database.
	 * @throws InvalidArgumentException if the user is not the user who sent the message,
	 *		or whom the message was sent. Or the message was already marked.
	 */
	public function markAsDeleted($message, $user) {
		if (empty($message)) {
			throw new NullPointerException("message");
		}
		if (empty($user)) {
			throw new NullPointerException("user");
		}
		$msgRows = $this->get()->where("%n = %i", self::VIEW_ID, $message);
		if ($msgRows->count() == 0) {
			throw new DataNotFoundException("message");
		}
		$msg = $msgRows->fetch();
		if ($msg[self::VIEW_FROM_ID] == $user && !($msg[self::VIEW_FROM_DESTROYED] == self::IS_READ_YES)) {
			if ($msg[self::VIEW_TO_DESTROYED] == self::IS_READ_YES) {
				$this->delete($message);
				return;
			}
			$update = array(self::DATA_DESTROYED_FROM => self::IS_READ_YES);
		}
		else if ($msg[self::VIEW_TO_ID] == $user && !($msg[self::VIEW_TO_DESTROYED] == self::IS_READ_YES)) {
			if ($msg[self::VIEW_FROM_DESTROYED] == self::IS_READ_YES) {
				$this->delete($message);
				return;
			}
			$update = array(self::DATA_DESTROYED_TO => self::IS_READ_YES);
		}
		else {
			throw new InvalidArgumentException("user");
		}
		if (!empty($update)) {
			dibi::update(self::getTable(), $update)
				->where("%n = %i", self::DATA_ID, $message)
				->execute();
		}
	}

	/**
	 * It marks the user's messages as read
	 *
	 * @param int $user User's ID.
	 * @throws NullPointerException if the $user is empty.
	 * @throws DibiDriverException if there is a problem to work with database.
	 */
	public function markAsRead($user) {
		if (empty($user)) {
			throw new NullPointerException("user");
		}
		dibi::update(self::getTable(),array(self::DATA_IS_READ => self::IS_READ_YES))
			->where("%n = %i", self::DATA_USER_TO, $user)
			->execute();
	}

	/**
	 * It returns all messages in user's message box.
	 *
	 * @param int $user User's ID.
	 * @return DibiDataSource
	 * @throws NullPointerException if the $user is empty.
	 * @throws DibiDriverException if there is a problem to work with database.
	 */
	public function read($user) {
		if (empty($user)) {
			throw new NullPointerException("user");
		}
		return $this->get()->where(
			"%n = %i", self::DATA_USER_FROM, $user,
			" %or %n = %i", self::DATA_USER_TO, $user
		);
	}

}
