<?php

/**
 * Test: Image factories.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



$image = Image::fromFile('images/logo.gif');
// logo.gif
Assert::same( 176, $image->width, 'width' );

Assert::same( 104, $image->height, 'height' );


$image = Image::fromBlank(200, 300, Image::rgb(255, 128, 0));
// blank
Assert::same( 200, $image->width, 'width' );

Assert::same( 300, $image->height, 'height' );
