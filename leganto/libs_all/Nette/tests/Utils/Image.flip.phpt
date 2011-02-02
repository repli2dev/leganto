<?php

/**
 * Test: Image flip.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';


$image = Image::fromFile('images/logo.gif');
$flipped = $image->resize(-100, -100);

Assert::same(file_get_contents(dirname(__FILE__) . '/Image.flip.expect'), $flipped->toString(Image::GIF));