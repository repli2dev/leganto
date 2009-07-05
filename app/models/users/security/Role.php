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

	/**
	 * It returns a name of MySQL table which the model work with.
	 *
	 * @return string
	 */
	public static function getTable() {
		$tables = Environment::getConfig('tables');
		return (!empty($tables->role) ? $tables->role : 'role');
	}


	/**
	 * It returns roles with they permission privileges.
	 *
	 * @return DibiDataSource
	 */
	public function getWithPrivileges() {
		return dibi::dataSource(
			"SELECT * FROM %n", self::getTable(),
			"LEFT JOIN %n USING(%n)", Permissions::getTable(), self::DATA_ID
		);
	}

	protected function tableName() {
		return self::getTable();
	}
	
}
