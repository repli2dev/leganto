<?php
/**
 * @author Jan Papousek
 */
abstract class AEntity extends EskymoObject implements IEntity
{

	private $id;

	/* PUBLIC METHODS */

	public function getId() {
		return $this->id;
	}

	public function isReadyToUpdate() {
		return isset($this->id);
	}

	public function isReadyToInsert() {
		// Check if the id is not set.
		if (isset($this->id)) {
			return FALSE;
		}
		// Check if all required columns are set
		foreach ($this->getVars() AS $var) {
			$reflection = $this->getReflection()->getProperty();
			if (Annotations::has($reflection, "Required") && !isset($this->$var)) {
				return FALSE;
			}
		}
		// If all passed, the entity is ready to be inserted
		return TRUE;
	}

	public function  loadDataFromRow(DibiRow $row) {
		// Foreach variable try to load data from a row
		foreach($this->getVars() AS $var) {
			$reflection = $this->getReflection()->getProperty($var);
			// The variables which has 'Skip' annotation will be skipped
			if (Annotations::has($reflection, "Skip")) {
				$toSkip = Annotations::get($reflection, "Skip");
				if (empty($toSkip) || $toSkip == "Load") {
					continue;
				}
			}
			// Check if there is an annotation to change the column name
			// (Defaultly the column name is the same as variable name)
			if (Annotations::has($reflection, "Load")) {
				$column = Annotations::get($reflection, "Load");
			}
			else {
				$column = $var;
			}
			if (isset($row[$column])) {
				$this->$var = $row[$column];
			}
		}
		$this->loadIdFromRow($row);
		return $this;
	}

	/* PROTECTED METHODS */

	abstract protected function loadIdFromRow(DibiRow $row);

	protected function setId($id) {
		$this->id = $id;
	}
}
