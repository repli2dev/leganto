<?php

/**
 * Test: Mail with template.
 *
 * @author     David Grudl
 * @package    Nette\Application
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Mail.inc';



// temporary directory
define('TEMP_DIR', dirname(__FILE__) . '/tmp');
TestHelpers::purge(TEMP_DIR);
Environment::setVariable('tempDir', TEMP_DIR);



$mail = new Mail();
$mail->addTo('Lady Jane <jane@example.com>');

$mail->htmlBody = new Template;
$mail->htmlBody->setFile('files/template.phtml');
$mail->htmlBody->registerFilter(new LatteFilter);

$mail->send();

Assert::match(file_get_contents(dirname(__FILE__) . '/Mail.template.expect'), TestMailer::$output);
