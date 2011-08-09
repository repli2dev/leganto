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

namespace CronModule;

use Nette\Application\Routers\Route;

class Routes {

	public static function add($router,$prefix = "cron/") {
		$router[] = new Route($prefix . "<presenter>/<action>", array(
			"module"		=> "cron",
			"presenter"		=> "Default",
			"action"		=> "default"
		));
	}

}
