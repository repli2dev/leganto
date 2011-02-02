<?php

/**
 * Test: Mail - textual body.
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

$mail->setBody('Žluťoučký kůň');

$mail->send();

Assert::match( <<<EOD
MIME-Version: 1.0
X-Mailer: Nette Framework
Date: %a%
From: John Doe <doe@example.com>
To: Lady Jane <jane@example.com>
Subject: Hello Jane!
Message-ID: <%h%@localhost>
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit

Žluťoučký kůň
EOD
, TestMailer::$output );
