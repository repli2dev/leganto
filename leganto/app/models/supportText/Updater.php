<?php

/**
 * Support text updater
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\SupportText;

use Leganto\ORM\Workers\IUpdater,
    Leganto\ORM\Workers\AWorker,
    Leganto\ORM\Workers\SimpleUpdater,
    Leganto\ORM\IEntity;

class Updater extends AWorker implements IUpdater {
	/* PUBLIC METHODS */

	public function update(IEntity $entity) {
		return SimpleUpdater::createUpdater("support_text", $this->connection)->update($entity);
	}

}