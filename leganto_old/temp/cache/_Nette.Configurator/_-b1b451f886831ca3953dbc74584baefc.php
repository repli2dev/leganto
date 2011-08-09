<?php //netteCache[01]000224a:2:{s:4:"time";s:21:"0.81882900 1311000000";s:9:"callbacks";a:1:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:54:"/home/Weby/Ostatni/preader/www/leganto/app/config.neon";i:2;i:1310999832;}}}?><?php
// source file /home/Weby/Ostatni/preader/www/leganto/app/config.neon

$container->addService('robotLoader', function($container) {
	$service = call_user_func(
		array ( 0 => 'Nette\\Configurator', 1 => 'createServicerobotLoader', ),
		$container
	);
	return $service;
}, array ( 0 => 'run', ));

$container->addService('authenticator', 'UserAuthenticator');

$container->addService('authorizator', 'UserAuthorizator');

$container->params['mail'] = array (
  'info' => 'info@ctenari.cz',
  'name' => 'Leganto team',
);

$container->params['facebook'] = array (
  'enable' => false,
  'apiKey' => false,
  'secret' => false,
);

$container->params['twitter'] = array (
  'enable' => false,
  'apiKey' => false,
  'secret' => false,
);

$container->params['debug'] = array (
  'enable' => true,
  'log' => $container->expand('%app_dir%/logs/php_error.log'),
  'testDir' => $container->expand('%app_dir%/TestModule/tests'),
);

$container->params['database'] = array (
  'profiler' => true,
  'driver' => 'mysql',
  'charset' => 'utf8',
  'lazy' => true,
  'username' => 'leganto_devel',
  'password' => 'Hublu.mer',
  'host' => 'zimodej.cz',
  'database' => 'leganto_devel',
);

define('APP_DIR', $container->params['appDir']);

Nette\Caching\Storages\FileStorage::$useDirectories = true;

foreach ($container->getServiceNamesByTag("run") as $name => $foo) { $container->getService($name); }
