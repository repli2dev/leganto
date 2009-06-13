<?php
/**
 * Model based on MySQL table 'expresion' containing text
 * used on the site, which has to be localized.
 *
 * @author Jan Papousek
 */
class Expresion extends ATableModel
{

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
	 * It return prepared query containing columns described
	 * id doc to MySQL table 'expresion'.
	 *
	 * @return DibiDataSource
	 * @throws DibiException if there is a problem to work with database.
	 */
	public function get() {
		return dibi::dataSource(
			"SELECT
				[id_expresion],
				[key],
				[value]
			 FROM [".$this->getTable()."]"
		);
	}

	public function getTable() {
		$tables = Environment::getConfig('tables');
		return ($tables->expresion ? $tables->expresion : 'expresion');
	}

	public function insert($input) {
		// TODO
	}

	public function update($id, $input) {
		// TODO
	}

}
?>
