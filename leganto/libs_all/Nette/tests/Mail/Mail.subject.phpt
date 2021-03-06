<?php

/**
 * Test: Mail subject.
 *
 * @author     David Grudl
 * @package    Nette\Application
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Mail.inc';



$mail = new Mail();
$mail->setSubject('Testovací ! <email> od žluťoučkého koně ...');
$mail->send();

Assert::match( 'MIME-Version: 1.0
X-Mailer: Nette Framework
Date: %a%
Subject: =?UTF-8?B?VGVzdG92YWPDrSAhIDxlbWFpbD4gb2Qgxb5sdcWlb3XEjWs=?=
	=?UTF-8?B?w6lobyBrb27EmyAuLi4=?=
Message-ID: <%h%@localhost>
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 7bit
', TestMailer::$output );

$mail = new Mail();
$mail->setSubject('veryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryverylongemail');
$mail->send();

Assert::match( 'MIME-Version: 1.0
X-Mailer: Nette Framework
Date: %a%
Subject: =?UTF-8?B?dmVyeXZlcnl2ZXJ5dmVyeXZlcnl2ZXJ5dmVyeXZlcnl2ZXI=?=
	=?UTF-8?B?eXZlcnl2ZXJ5dmVyeXZlcnl2ZXJ5dmVyeXZlcnl2ZXJ5dmVyeXZlcnk=?=
	=?UTF-8?B?dmVyeXZlcnl2ZXJ5dmVyeXZlcnlsb25nZW1haWw=?=
Message-ID: <%h%@localhost>
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 7bit
', TestMailer::$output );

$mail = new Mail();
$mail->setSubject('veryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryveryverylongemailšššššššššššššššš');
$mail->send();

Assert::match( 'MIME-Version: 1.0
X-Mailer: Nette Framework
Date: %a%
Subject: =?UTF-8?B?dmVyeXZlcnl2ZXJ5dmVyeXZlcnl2ZXJ5dmVyeXZlcnl2ZXI=?=
	=?UTF-8?B?eXZlcnl2ZXJ5dmVyeXZlcnl2ZXJ5dmVyeXZlcnl2ZXJ5dmVyeXZlcnk=?=
	=?UTF-8?B?dmVyeXZlcnlsb25nZW1haWzFocWhxaHFocWhxaHFocWhxaHFocWhxaE=?=
	=?UTF-8?B?xaHFocWhxaE=?=
Message-ID: <%h%@localhost>
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 7bit
', TestMailer::$output );

$mail = new Mail();
$mail->setSubject('==========================================================================================ššššššššššššššššš');
$mail->send();

Assert::match( 'MIME-Version: 1.0
X-Mailer: Nette Framework
Date: %a%
Subject: =?UTF-8?B?PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT0=?=
	=?UTF-8?B?PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT0=?=
	=?UTF-8?B?PT09PT09PT09PT09PT3FocWhxaHFocWhxaHFocWhxaHFocWhxaHFoQ==?=
	=?UTF-8?B?xaHFocWhxaE=?=
Message-ID: <%h%@localhost>
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 7bit

', TestMailer::$output );
