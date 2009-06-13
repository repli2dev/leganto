<?php
/**
 * Abstract class designed to be extended by classes
 * representing model on the MySQL tables.
 *
 * @author Jan Papousek
 */
abstract class ATableModel extends Object implements ITableModel
{

	public function delete($id) {
		if (empty($id)) {
			throw new NullPointerException("id");
		}
		$deleted = dibi::delete($this->getTable())
			->where("[".$this->getIdentificator()."] = %i",$id)->execute();
		if ($deleted < 1) {
			throw new DataNotFoundException("id");
		}
	}

	public function insert($input) {
		dibi::insert($this->getTable(), $input)->execute();
		return dibi::insertId();
	}

	public function update($id, $input) {
		if (empty($id)) {
			throw new NullPointerException("id");
		}
		$rows = $this->get()->where("[".$this->getIdentificator()."] = %i", $id);
		if ($rows->count() == 0) {
			throw new DataNotFoundException("id");
		}
		dibi::update($this->getTable(), $input)->execute();
	}

}
?>
