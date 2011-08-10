<?php

/**
 * Support text deleter
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\SupportText;

use Leganto\ORM\Workers\IDeleter,
    Leganto\ORM\Workers\SimpleDeleter,
    Leganto\ORM\Workers\AWorker;

class Deleter extends AWorker implements IDeleter {

	public function delete($id) {
		return SimpleDeleter::createDeleter("support_text", $this->connection)->delete($id);
	}

}