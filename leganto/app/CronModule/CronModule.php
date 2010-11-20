<?php
class CronModule
{

	public static function createRouter($prefix = "cron/") {
		$router = new MultiRouter();

		$router[] = new Route($prefix . "<presenter>/<action>", array(
			"module"		=> "cron",
			"presenter"		=> "Default",
			"action"		=> "default"
		));

		return $router;
	}

}
