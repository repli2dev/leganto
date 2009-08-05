<?php
/**
 * The model containing permissions of the roles.
 *
 * @author Jan Papousek
 */
class Permissions extends ATableModel
{

	const ACTION_ALL = NULL;

	const ACTION_EDIT = "edit";

	const ACTION_EDIT_ALL = "edit_all";

	const ACTION_INSERT = "insert";

	const ACTION_READ = "read";

	const ACTION_READ_ALL = "read_all";

	const DATA_ACTION = "action";

	const DATA_ID = "id_permission";

	const DATA_MODULE = "id_module";

	const DATA_ROLE = "id_role";

	/**
	 * It returns a name of MySQL table which the model work with.
	 *
	 * @return string
	 */
	public static function getTable() {
		return 'permission';
	}

}