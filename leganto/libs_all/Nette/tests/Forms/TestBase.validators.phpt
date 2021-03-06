<?php

/**
 * Test: TextBase validators.
 *
 * @author     David Grudl
 * @package    Nette\Forms
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



$control = new TextInput();
$control->value = '';
Assert::false( TextBase::validateEmail($control) );


$control->value = '@.';
Assert::false( TextBase::validateEmail($control) );


$control->value = 'name@a-b-c.cz';
Assert::true( TextBase::validateEmail($control) );


$control->value = "name@\xc5\xbelu\xc5\xa5ou\xc4\x8dk\xc3\xbd.cz"; // name@�lu�ou�k�.cz
Assert::true( TextBase::validateEmail($control) );


$control->value = "\xc5\xbename@\xc5\xbelu\xc5\xa5ou\xc4\x8dk\xc3\xbd.cz"; // �name@�lu�ou�k�.cz
Assert::false( TextBase::validateEmail($control) );
