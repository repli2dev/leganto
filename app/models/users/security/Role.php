<?php
/**
 * Model containing roles.
 *
 * @author Jan Papousek
 */
class Role extends ATableModel
{

	const DATA_ID = "id_role";

	const DATA_NAME = "name";

	protected function identificator() {
		return self::DATA_ID;
	}

	/**
	 * It returns a name of MySQL table which the model work with.
	 *
	 * @return string
	 */
	public static function getTable() {
		$tables = Environment::getConfig('tables');
		return (!empty($tables->role) ? $tables->role : 'role');
	}

	protected function requiredColumns() {
		return array(self::DATA_NAME);
	}

	protected function tableName() {
		return self::getTable();
	}
	
}
