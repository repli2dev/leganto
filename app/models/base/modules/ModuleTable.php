<?php
/**
 * This class represents model containg module tables.
 *
 * @author Jan Papousek
 */
class ModuleTable extends ATableModel
{

	const DATA_ID = "id_module_table";

	const DATA_MODULE = "id_module";
	
	const DATA_NAME_COLUMN = "name_column";
	
	const DATA_ID_COLUMN = "identificator_column";

	const DATA_TABLE = "table";

	/**
	 * It deletes tables based on module.
	 *
	 * @param int $module Module ID.
	 * @throws NullPointerException if the $module is empty
	 * @throws DibiDriverException if there is a problem to work with database.
	 */
	public function deleteByModule($module) {
		dibi::delete(self::getTable())
			->where("%n = %i", self::DATA_MODULE, $module)
			->execute();
	}

	/**
	 * It returns a name of MySQL table which the model work with.
	 *
	 * @return string
	 */
	public static function getTable() {
		$tables = Environment::getConfig('tables');
		return (!empty($tables->module_table) ? $tables->module_table : 'module_table');
	}

	protected function tableName() {
		return self::getTable();
	}

}
?>
