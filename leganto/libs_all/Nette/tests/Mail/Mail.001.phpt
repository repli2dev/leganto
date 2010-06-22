<?php

/**
 * Test: Nette\Mail\Mail - textual body.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette\Application
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../NetteTest/initialize.php';

require dirname(__FILE__) . '/Mail.inc';



$mail = new Mail();

$mail->setFrom('John Doe <doe@example.com>');
$mail->addTo('Lady Jane <jane@example.com>');
$mail->setSubject('Hello Jane!');

$mail->setBody('Žluťoučký kůň');

$mail->send();



__halt_compiler();
