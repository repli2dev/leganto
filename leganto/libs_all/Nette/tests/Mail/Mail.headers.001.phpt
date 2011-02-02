<?php

/**
 * Test: Mail invalid headers.
 *
 * @author     David Grudl
 * @package    Nette\Application
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Mail.inc';



$mail = new Mail();

try {
	$mail->setHeader('', 'value');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('InvalidArgumentException', "Header name must be non-empty alphanumeric string, '' given.", $e );
}

try {
	$mail->setHeader(' name', 'value');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('InvalidArgumentException', "Header name must be non-empty alphanumeric string, ' name' given.", $e );
}

try {
	$mail->setHeader('n*ame', 'value');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('InvalidArgumentException', "Header name must be non-empty alphanumeric string, 'n*ame' given.", $e );
}
