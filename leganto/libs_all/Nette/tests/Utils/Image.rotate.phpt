<?php

/**
 * Test: Image rotating.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



if (GD_BUNDLED === 0) {
	TestHelpers::skip('Requires PHP extension GD in bundled version.');
}



$image = Image::fromFile('images/logo.gif');
$rotated = $image->rotate(30, Image::rgb(0, 0, 0));


Assert::same(file_get_contents(dirname(__FILE__) . '/Image.rotate.expect'), $rotated->toString(Image::GIF));