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
namespace Leganto\DB\SupportText;
use Leganto\ORM\Workers\IUpdater,
	Leganto\ORM\Workers\SimpleUpdater;

class supportTextUpdater implements IUpdater {
	/* PUBLIC METHODS */

	public function update(IEntity $entity){
		return SimpleUpdater::createUpdater("support_text")->update($entity);
	}

}