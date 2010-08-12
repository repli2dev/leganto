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
class supportTextSelector implements ISelector
{

	/* PUBLIC METHODS */
	
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [support_text]");
	}

	public function findAllByCategory($category) {
		if(empty($category)) {
			throw new NullPointerException("Empty category id.");
		}
		return dibi::dataSource("SELECT * FROM [support_text] WHERE id_support_category = %i ORDER BY weight ASC",$category);
	}
	
	/** @return BookEntity */
	public function find($id) {
		return Leganto::supportText()
			->fetchAndCreate(
				dibi::dataSource("SELECT * FROM [support_text] WHERE [id_support_text] = %i", $id)
			);
	}

	public function search($keyword) {
		if (empty($keyword)) {
			throw new NullPointerException("keyword");
		}
		$keywords = preg_split('/ /', $keyword);
		$conditions = "";
		foreach($keywords AS $word) {
		    if (!empty($conditions)) {
			$conditions .= " AND ";
		    }
		    $word = "%".mysql_escape_string($word)."%";
		    $conditions .= "
			([name] LIKE '$word' OR
			[text] LIKE '$word')";
		}
		return dibi::dataSource("SELECT * FROM [support_text] " . (empty($conditions) ? "" : " WHERE " . $conditions));
	}

}