<?php

/**
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
class captchaSelector implements ISelector {
	/* PUBLIC METHODS */

	/**
	 * Find all captcha questions
	 * @return DibiDataSource
	 */
	public function findAll() {
		return dibi::dataSource("SELECT * FROM [captcha]");
	}

	/** @return BookEntity */
	public function find($id) {
		return Leganto::captcha()
			->fetchAndCreate(
				dibi::dataSource("SELECT * FROM [captcha] WHERE [id_captcha] = %i", $id)
		);
	}

	/**
	 * Return random captcha question in certain language
	 * @param int $language id of language
	 * @return array
	 */
	public function findFromLanguageRandom($language) {
		if (empty($language)) {
			throw new NullPointerException("Empty language id.");
		}
		$data = dibi::dataSource("SELECT * FROM [captcha] WHERE id_language = %i ORDER BY RAND() LIMIT 1", $language)->fetchAll();
		return $data[0];
	}

}