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
class WebModule {

	public static function createRouter($prefix = "/") {
		$router = new MultiRouter();

		$router[] = new Route($prefix . "<presenter>/<action>", array(
			    "module" => "web",
			    "presenter" => "default",
			    "action" => "default"
			));

		return $router;
	}

	public static function getModuleDir() {
		return dirname(__FILE__);
	}

}

