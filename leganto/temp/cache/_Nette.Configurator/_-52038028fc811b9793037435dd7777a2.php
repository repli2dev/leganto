<?php //netteCache[01]000224a:2:{s:4:"time";s:21:"0.76319800 1313742302";s:9:"callbacks";a:1:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:54:"/home/Weby/Ostatni/preader/www/leganto/app/config.neon";i:2;i:1313742299;}}}?><?php
// source file /home/Weby/Ostatni/preader/www/leganto/app/config.neon

$container->addService('robotLoader', function($container) {
	$service = call_user_func(
		array ( 0 => 'Nette\\Configurator', 1 => 'createServicerobotLoader', ),
		$container
	);
	return $service;
}, array ( 0 => 'run', ));

$container->addService('authenticator', 'Leganto\\DB\\User\\Authenticator');

$container->addService('authorizator', 'Leganto\\ACL\\Authorizator');

$container->params['mail'] = array (
  'info' => 'info@ctenari.cz',
  'name' => 'Leganto team',
);

$container->params['facebook'] = array (
  'enable' => 'truze',
  'apiKey' => 175434332634,
  'secret' => 'a482d8bd44441006ec1d5613d1b8195c',
);

$container->params['twitter'] = array (
  'enable' => true,
  'apiKey' => 'jzH2ermcGbzQ4SaWCj230Q',
  'secret' => 'LxFR8fM47HDk0s60cnef4EiTMIk2bGY32v5swVwNx4',
);

$container->params['cron'] = array (
  'key' => 'gRpE6hrKEltAo63sOnQA',
);

$container->params['debug'] = array (
  'enable' => true,
);

$container->params['database'] = array (
  'driver' => 'mysql',
  'charset' => 'utf8',
  'lazy' => true,
  'username' => 'leganto_devel',
  'password' => 'Hublu.mer',
  'host' => 'zimodej.cz',
  'database' => 'leganto_devel',
);

date_default_timezone_set('Europe/Prague');

define('APP_DIR', $container->params['appDir']);

define('WWW_DIR', $container->params['wwwDir']);

Nette\Caching\Storages\FileStorage::$useDirectories = true;

foreach ($container->getServiceNamesByTag("run") as $name => $foo) { $container->getService($name); }
