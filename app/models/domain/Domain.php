<?php
/**
 * The model containing domains running on Reader system.
 *
 * @author Jan Papousek
 */
class Domain extends ATableModel
{

	const DATA_ID = "id_domain";

	const DATA_LANGUAGE = "id_language";

	const DATA_DEFAULT_ROLE = "id_role";

	const DATA_URI = "uri";

	const DATA_EMAIL = "email";

	/**
	 * It returns prepared query containing columns described
	 * id doc to MySQL table 'domain'.
	 *
	 * @return DibiDataSource
	 * @throws DibiException if there is a problem to work with database.
	 */
	public function get() {
		return dibi::dataSource(
			"SELECT *
			 FROM [".$this->getTable()."]"
		);
	}

	public function getIdentificator() {
		return self::DATA_ID;
	}

	public function getTable() {
		$tables = Environment::getConfig('tables');
		return (!empty($tables->domain) ? $tables->domain : 'domain');
	}

	// TODO: comment
	public function insert($input) {
		$existence = new DomainExistence($input[self::DATA_URI]);
		if ($existence->exists()) {
			return (-1);
		}
		if (empty($input[Language::DATA_ID])) {
			throw new NullPointerException("input[id_language]");
		}
		$language = new Language();
		if ($language->get()->where("%n = %i",Language::DATA_ID,$input[self::DATA_LANGUAGE])->count() == 0) {
			throw new DataNotFoundException("input[id_language]");
		}
		parent::insert($input);
	}

}
?>
