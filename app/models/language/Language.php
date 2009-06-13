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

	/**
	 * It returns prepared query containing columns described
	 * id doc to MySQL table 'language'.
	 *
	 * @return DibiDataSource
	 * @throws DibiException if there is a problem to work with database.
	 */
	public function get() {
		return dibi::dataSource(
			"SELECT
				[id_language],
				[name],
				[locale]
			 FROM [".$this->getTable()."]"
		);
	}

	public function getIdentificator() {
		return self::DATA_ID;
	}

	public function getTable() {
		$tables = Environment::getConfig('tables');
		return (!empty($tables->language) ? $tables->language : 'language');
	}

	public function insert($input) {
		$existence = new LanguageExistence(
			Tools::arrayGet($input, "locale"),
			Tools::arrayGet($input, "name")
		);
		if ($existence->exists()) {
			return (-1);
		}
		parent::insert($input);
	}
}
?>
