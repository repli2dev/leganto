<?php
/*
 * The web basis called Eskymo.
 *
 * @copyright   Copyright (c) 2004, 2009 Jan Papousek, Jan Drabek
 * @link        [--- ESKYMO REPOSITORY LINK ---]
 * @category    Eskymo
 * @package     Eskymo\Modules
 * @version     2009-07-04
 */

/*namespace Reader\Base\Modules;*/

/**
 * This class represents model containg module tables.
 *
 * @author      Jan Papousek
 * @version     2009-07-04
 * @package     Eskymo\Modules
 */
class ModuleTable extends /*Eskymo\*/ATableModel
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

}