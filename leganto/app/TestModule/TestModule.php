<?php
class TestModule
{

	public static function createRouter($prefix = "test/") {
		$router = new MultiRouter();

		$router[] = new Route(
			$prefix . "<action>/<class>/<method>",
			array(
				"module"	=> "test",
				"presenter" => "Test",
				"action"	=> "default",
				"class"		=> NULL,
				"method"	=> NULL
			)
		);
		return $router;
	}

}
