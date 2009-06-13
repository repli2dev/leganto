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
	 *			It return prepared query containing columns described
	 *			id doc to MySQL table 'language'.
	 *
	 * @return	DibiDataSource
	 * @throws	DibiException if there is a problem to work with database.
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
		return "id_language";
	}

	public function getTable() {
		$tables = Environment::getConfig('tables');
		return ($tables->language ? $tables->language : 'language');
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
