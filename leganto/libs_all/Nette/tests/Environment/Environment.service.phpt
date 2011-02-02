<?php

/**
 * Test: Environment services.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



Assert::same( 'HttpResponse', Environment::getHttpResponse()->reflection->name );


Assert::same( 'Application', Environment::getApplication()->reflection->name );


Environment::setVariable('tempDir', dirname(__FILE__) . '/tmp');
Assert::same( 'Cache', Environment::getCache('my')->reflection->name );


/* in PHP 5.3
Environment::setServiceAlias('IUser', 'xyz');
Assert::same('xyz', Environment::getXyz()->reflection->name );
*/
