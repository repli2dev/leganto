<?php

/**
 * Test: Debug notices and warnings logging.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



// Setup environment
$_SERVER['HTTP_HOST'] = 'nette.org';

$errorLog = dirname(__FILE__) . '/log/php_error.log';
TestHelpers::purge(dirname($errorLog));

Debug::$consoleMode = FALSE;
Debug::$mailer = 'testMailer';

Debug::enable(Debug::PRODUCTION, $errorLog, 'admin@example.com');

function testMailer() {}


// throw error
$a++;

Assert::match('%a%PHP Notice:  Undefined variable: a in %a%', file_get_contents(dirname($errorLog) . '/php_error.log'));
Assert::true(is_file(dirname($errorLog) . '/php_error.log.monitor'));
