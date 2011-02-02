<?php

/**
 * Common code for Mail test cases.
 */



// Setup environment
Mail::$defaultMailer = 'TestMailer';


class TestMailer implements IMailer
{
	public static $output;

	function send(Mail $mail)
	{
		self::$output = $mail->generateMessage();
	}

}
