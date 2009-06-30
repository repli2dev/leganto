<?php
/**
 * The model containing permissions of the roles.
 *
 * @author Jan Papousek
 */
class Permissions extends ATableModel
{

	const DATA_ACTION = "action";

	const DATA_ID = "id_permission";

	const DATA_MODULE = "id_module";

	const DATA_ROLE = "id_role";

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
		return (!empty($tables->permission) ? $tables->permission : 'permission');
	}

	protected function requiredColumns() {
		return array(
			self::DATA_MODULE,
			self::DATA_ROLE
		);
	}

	protected function tableName() {
		return self::getTable();
	}

}
?>
