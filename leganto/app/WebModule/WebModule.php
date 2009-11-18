<?php

class WebModule
{

	public static function createRouter($prefix = "/") {
		$router = new MultiRouter();

		$router[] = new Route($prefix . "<presenter>/<action>", array(
			"module"		=> "web",
			"presenter"		=> "default",
			"action"		=> "default"
		));

		return $router;
	}


	public static function getModuleDir() {
		return dirname(__FILE__);
	}

}

