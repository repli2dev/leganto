<?php

/**
 * Test: Tools::detectMimeType()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



Assert::same( 'image/gif', Tools::detectMimeType('images/logo.gif') );
Assert::same( 'application/octet-stream', Tools::detectMimeType('files/bad.ppt') );
