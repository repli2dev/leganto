<?php

/**
 * Test: Mail - textual and HTML body.
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

$mail->setHTMLBody('<b>Žluťoučký kůň</b>');

$mail->send();

Assert::match( <<<EOD
MIME-Version: 1.0
X-Mailer: Nette Framework
Date: %a%
From: John Doe <doe@example.com>
To: Lady Jane <jane@example.com>
Subject: Hello Jane!
Message-ID: <%h%@localhost>
Content-Type: multipart/alternative;
	boundary="--------%h%"

----------%h%
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 7bit

Sample text
----------%h%
Content-Type: text/html; charset=UTF-8
Content-Transfer-Encoding: 8bit

<b>Žluťoučký kůň</b>
----------%h%--
EOD
, TestMailer::$output );
