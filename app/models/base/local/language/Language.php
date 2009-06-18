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

	/**
	 * It insert an entity to the database.
	 *
	 * @param array|mixed $input The input data, keys are names of the columns
	 *		and values are content.
	 * @return int Identificator of the new entity in database
	 *		or '-1' if the entity has already existed.
	 * @throws InvalidArgumentException if the input is not an array.
	 * @throws NullPointerException if the input is empty or does not contain
	 *		all necessary columns.
	 * @throws DibiException if there is a problem to work with database.
	 */
	public function insert($input) {
		if (!is_array($input)) {
			throw new InvalidArgumentException("input");
		}
		$existence = new LanguageExistence(
			Tools::arrayGet($input, "locale"),
			Tools::arrayGet($input, "name")
		);
		if ($existence->exists()) {
			return (-1);
		}
		parent::insert($input);
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
