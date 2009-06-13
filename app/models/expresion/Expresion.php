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
	 *
	 * @var string
	 */

	public function delete($id) {
		if (empty($id)) {
			throw new NullPointerException("id");
		}
		$deleted = dibi::delete($this->getTable())
			->where("[id_expresion] = %i",$id)->execute();
		if ($deleted < 1) {
			throw new DataNotFoundException("id");
		}
	}

	/**
	 * It returns prepared query containing columns described
	 * id doc to MySQL table 'expresion'.
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
		return ($tables->expresion ? $tables->expresion : 'expression');
	}

	public function insert($input) {
		// TODO
	}

	public function update($id, $input) {
		// TODO
	}

}
?>
