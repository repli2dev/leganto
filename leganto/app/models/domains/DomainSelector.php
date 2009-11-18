<?php
class DomainSelector implements ISelector
{

	public function find($id) {
		if (empty($id)) {
			throw new NullPointerException("id");
		}
		return SimpleEntityFactory::createEntityFactory("domain")
			->fetchAndCreate(
				$this->findAll()->where("[id_domain] = %i", $id)
			);
	}

	/** @return DibiDataSource */
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [domain]");
	}

}
