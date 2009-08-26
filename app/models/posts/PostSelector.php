<?php
/**
 * @author Jan Papousek
 */
class PostSelector implements ISelector
{

	const OPINION = 1;

	public function findAll() {
		return dibi::DataSource("SELECT * FROM [view_post]");
	}

	public function findAllByIdAndType($id, $type) {
		if (empty($type)) {
			throw new NullPointerException("type");
		}
		if (empty($id)) {
			throw new NullPointerException("id");
		}
		if (!in_array($type, array(self::OPINION))) {
				throw new NotSupportedException("The entity type is not supported");
		}
		return dibi::DataSource("SELECT * FROM [view_post] WHERE [id_discussable] = %i", $type, " AND [id_post] = %i", $id);
	}

	public function find($id) {
		if (empty($id)) {
			throw new NullPointerException("id");
		}
		return Leganto::posts()->fetchAndCreate(dibi::DataSource("SELECT * FROM [view_post] WHERE [id_post] = %i", $id));
	}
}
