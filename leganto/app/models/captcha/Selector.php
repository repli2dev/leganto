<?php

/**
 * Text captcha selector
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\Captcha;

use Leganto\ORM\Workers\AWorker,
    Leganto\ORM\Workers\ISelector,
    InvalidArgumentException,
    Leganto\DB\Factory;

class captchaSelector extends AWorker implements ISelector {
	/* PUBLIC METHODS */

	/**
	 * Find all captcha questions
	 * @return DibiDataSource
	 */
	public function findAll() {
		return $this->connection->dataSource("SELECT * FROM [captcha]");
	}

	/** @return Entity */
	public function find($id) {
		return Factory::captcha()
				->fetchAndCreate(
					$this->connection->dataSource("SELECT * FROM [captcha] WHERE [id_captcha] = %i", $id)
		);
	}

	/**
	 * Return random captcha question in certain language
	 * @param int $language id of language
	 * @return array
	 * @throws InvalidArgumentException if language is empty
	 */
	public function findFromLanguageRandom($language) {
		if (empty($language)) {
			throw new InvalidArgumentException("Empty language id.");
		}
		$data = $this->connection->dataSource("SELECT * FROM [captcha] WHERE id_language = %i ORDER BY RAND() LIMIT 1", $language)->fetchAll();
		return $data[0];
	}

}