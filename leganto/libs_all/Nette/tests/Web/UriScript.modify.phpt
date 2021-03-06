<?php

/**
 * Test: UriScript modify.
 *
 * @author     David Grudl
 * @package    Nette\Web
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



$uri = new UriScript('http://nette.org:8080/file.php?q=search');
$uri->path = '/test/';
$uri->scriptPath = '/test/index.php';

Assert::same( '/test/index.php',  $uri->scriptPath );
Assert::same( 'http://nette.org:8080/test/',  $uri->baseUri );
Assert::same( '/test/',  $uri->basePath );
Assert::same( '',  $uri->relativeUri );
Assert::same( '',  $uri->pathInfo );
Assert::same( 'http://nette.org:8080/test/?q=search',  $uri->absoluteUri );
