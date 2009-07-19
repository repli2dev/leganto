<?php
/*
 * The web basis called Eskymo.
 *
 * @copyright   Copyright (c) 2004, 2009 Jan Papousek, Jan Drabek
 * @link        [--- ESKYMO REPOSITORY LINK ---]
 * @category    Eskymo
 * @package     Eskymo\Site
 * @version     2009-07-04
 */

/*namespace Eskymo\Locales;*/

/**
 * The model containing domains which are used by site instances.
 *
 * @author      Jan Papousek
 * @version     2009-07-08
 * @package     Eskymo\Site
 */
class Domain extends ATableModel
{

	const DATA_ID = "id_domain";

	const DATA_LANGUAGE = "id_language";

	const DATA_DEFAULT_ROLE = "id_role";

	const DATA_URI = "uri";

	const DATA_EMAIL = "email";

	/**
	 * It returns a name of MySQL table which the model work with.
	 *
	 * @return string
	 */
	public static function getTable() {
		$tables = Environment::getConfig('tables');
		return (!empty($tables->domain) ? $tables->domain : 'domain');
	}
}
