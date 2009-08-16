<?php
/**
 * @author Jan Papousek
 */
class TagEntity extends AEntity
{

	/* PUBLIC ATTRIBUTES */

	/**
	 * @Required
	 * @Save(id_language)
	 * @Load(id_language)
	 */
	public $languageId;

	/*
	 * @Required
	 */
	public $name;

	/* PROTETED METHODS */

	protected function loadIdFromRow(DibiRow $row) {
		$this->setId($row["id_tag"]);
	}

}
