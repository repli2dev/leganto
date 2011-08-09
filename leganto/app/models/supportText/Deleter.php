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
use Leganto\ORM\Workers\IDeleter,
    Leganto\ORM\Workers\SimpleDeleter;

class Deleter implements IDeleter {
	/* PUBLIC METHODS */

	public function delete($id){
		return SimpleDeleter::createDeleter("support_text")->delete($id);
	}

}