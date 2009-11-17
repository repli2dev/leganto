<?php
/**
 * The source file is subject to the license located on web
 * "http://code.google.com/p/preader/".
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (repli2dev@gmail.com)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 */

/**
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$Id$
 */
class HelpSelector implements ISelector
{

	/* PUBLIC METHODS */
	
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [help]");
	}

	/** @return HelpEntity */
	public function findRandomByCategory($category) {
		$source = dibi::dataSource("SELECT * FROM [help] ORDER BY %sql ","RAND()");
		if (!empty($category)) {
			$source->where("[category] = %s",$category);
		}
		$source->applyLimit(1);
		return Leganto::help()->fetchAndCreate($source);
	}

	/** @return HelpEntity */
	public function find($id) {
		return Leganto::help()
			->fetchAndCreate(
				dibi::dataSource("SELECT * FROM [help] WHERE [id_help] = %i", $id)
			);
	}

}