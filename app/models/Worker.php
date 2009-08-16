<?php
/**
 * @author Jan Papousek
 */
abstract class Worker extends EskymoObject
{

	/**
	 * @var ITableModel
	 */
	private $model;

	/**
	 * @return ITableModel
	 */
	abstract protected function createModel();

	/**
	 * @return ITableModel
	 */
	protected function getModel() {
		if (empty($this->model)) {
			$this->model = $this->createModel();
		}
		return $this->model;
	}

	/**
	 * It returns an array exctracted from entity
	 *
	 * @param AEntity $entity Entity which is extracted
	 * @param string $annotation Annotation which is used to replace the variable name to key name
	 * @return array
	 */
	protected function getArrayFromEntity(AEntity $entity, string $annotation) {
		$result = array();
		// Foreach variable try to load data from a row
		foreach($entity->getVars() AS $var) {
			if (!iseet($entity->$var)) {
				continue;
			}
			$reflection = $entity->getReflection()->getProperty();
			// The variables which has 'Skip' annotation will be skipped
			if (Annotations::has($reflection, "Skip")) {
				$toSkip = Annotations::get($reflection, $annotation);
				if (empty($toSkip) || in_array($annotation, $toSkip)) {
					continue;
				}
			}
			// Check if there is an annotation to change the column name
			// (Defaultly the column name is the same as variable name)
			if (Annotations::has($reflection, $annotation)) {
				$column = Annotations::get($reflection, $annotation);
			}
			else {
				$column = $var;
			}
			$result[$column] = trim($entity->$var);
		}
		return $result;
	}

}
