<?php

class ApiModule
{

	public static function createRouter($prefix = "api/") {
		$router = new MultiRouter();

		$router[] = new Route($prefix . "<presenter>/<action>", array(
			"module"		=> "api",
			"presenter"		=> "View",
			"action"		=> "available"
		));

		return $router;
	}


}

