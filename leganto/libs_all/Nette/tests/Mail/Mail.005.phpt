<?php

/**
 * Test: Mail - textual and HTML body with attachment.
 *
 * @author     David Grudl
 * @package    Nette\Application
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Mail.inc';



$mail = new Mail();

$mail->setFrom('John Doe <doe@example.com>');
$mail->addTo('Lady Jane <jane@example.com>');
$mail->setSubject('Hello Jane!');

$mail->setBody('Sample text');

$mail->setHTMLBody('<b>Sample text</b>');

$mail->addAttachment('files/example.zip');

$mail->send();

Assert::match(file_get_contents(dirname(__FILE__) . '/Mail.005.expect'), TestMailer::$output);
