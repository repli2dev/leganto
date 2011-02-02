<?php

/**
 * Test: Debug::timer()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



Debug::timer();

sleep(1);

Debug::timer('foo');

sleep(1);

Assert::same( 2.0, round(Debug::timer(), 1) );

Assert::same( 1.0, round(Debug::timer('foo'), 1) );
