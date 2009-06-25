<?php
/**
 *				Model looking after the languages used on the site.
 *				More info can be found in doc to MySQL table 'language'.
 *
 * @author		Jan Papousek
 */
class Language extends ATableModel
{

	/**
	 * The identificator column name.
	 *
	 * @var string
	 */
	const DATA_ID = "id_language";

	/**
	 * The name column name.
	 *
	 * @var string
	 */
	const DATA_NAME = "name";

	/**
	 * The locale column name.
	 *
	 * @var string
	 */
	const DATA_LOCALE = "locale";

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
		return (!empty($tables->language) ? $tables->language : 'language');
	}

	protected function requiredColumns() {
		return array(
			self::DATA_LOCALE,
			self::DATA_NAME
		);
	}

	protected function tableName() {
		return self::getTable();
	}
}
?>
