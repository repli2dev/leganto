<?php

/**
 * Test: ConfigAdapterIni section.
 *
 * @author     David Grudl
 * @package    Nette\Config
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



$config = Config::fromFile('config1.ini', 'development');
Assert::equal( new Config(array(
	'database' => new Config(array(
		'params' => new Config(array(
			'host' => 'dev.example.com',
			'username' => 'devuser',
			'password' => 'devsecret',
			'dbname' => 'dbname',
		)),
		'adapter' => 'pdo_mysql',
	)),
	'timeout' => '10',
	'display_errors' => '1',
	'html_errors' => '',
	'items' => new Config(array(
		'0' => '10',
		'1' => '20',
	)),
	'webname' => 'the example',
)), $config );
