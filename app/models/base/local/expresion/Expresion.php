<?php
/**
 * Model based on MySQL table 'expresion' containing text
 * used on the site, which has to be localized.
 *
 * @author Jan Papousek
 */
class Expresion extends ATableModel
{

	/**
	 * The identificator column name.
	 *
	 * @var string
	 */
	const DATA_ID = "id_expression";

	/**
	 * The language identificator column name.
	 *
	 * @var string
	 */
	const DATA_LANGUAGE = "id_language";

	/**
	 * The key column name.
	 *
	 * @var string
	 */
	const DATA_KEY = "key";

	/**
	 * The value column name.
	 */
	const DATA_VALUE = "value";

	protected function identificator() {
		return self::DATA_ID;
	}

	/**
	 * It insert an entity to the database.
	 *
	 * @param array|mixed $input The input data, keys are names of the columns
	 *		and values are content.
	 * @return int Identificator of the new entity in database
	 *		or '-1' if the entity has already existed.
	 * @throws NullPointerException if the input is empty or does not contain
	 *		all necessary columns.
	 * @throws DataNotFoundException if the language specified by input[id_language]
	 *		does not exist.
	 * @throws DibiException if there is a problem to work with database.
	 */
	public function insert(array $input) {
		if (!is_array($input)) {
			throw new InvalidArgumentException("input");
		}
		$languages = new Language();
		if ($languages->get()->where("%n = %i", Language::DATA_ID, $input[self::DATA_LANGUAGE])->count() == 0) {
			throw new DataNotFoundException("input[" . self::DATA_LANGUAGE . "]");
		}
		parent::insert($input);
	}

	/**
	 * It returns a name of MySQL table which the model work with.
	 *
	 * @return string
	 */
	public static function getTable() {
		$tables = Environment::getConfig('tables');
		return ($tables->expresion ? $tables->expresion : 'expression');
	}

	protected function requiredColumns() {
		$users = new Users();
		Users::
		return array(self::DATA_KEY,self::DATA_VALUE);
	}

	protected function tableName() {
		return self::getTable();
	}

}
?>
