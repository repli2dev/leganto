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
		return (!empty($tables->domain) ? $tables->domain : 'domain');
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
	 * @throws DibiDriverException if there is a problem to work with database.
	 */
	public function insert(array $input) {
		if (!is_array($input)) {
			throw new InvalidArgumentException("input");
		}
		$existence = new DomainExistence($input[self::DATA_URI]);
		if ($existence->exists()) {
			return (-1);
		}
		if (empty($input[Language::DATA_ID])) {
			throw new NullPointerException("input[".self::DATA_LANGUAGE."]");
		}
		$language = new Language();
		if ($language->get()->where("%n = %i",Language::DATA_ID,$input[self::DATA_LANGUAGE])->count() == 0) {
			throw new DataNotFoundException("input[".self::DATA_LANGUAGE."]");
		}
		parent::insert($input);
	}

	protected function requiredColumns() {
		return array(
			self::DATA_LANGUAGE,
			self::DATA_URI,
			self::DATA_EMAIL
		);
	}

	protected function tableName() {
		return self::getTable();
	}

}
?>
