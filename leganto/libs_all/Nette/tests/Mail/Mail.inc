<?php

/**
 * Common code for Mail test cases.
 */



// Setup environment
Mail::$defaultMailer = 'FileMailer';


class FileMailer implements IMailer
{

	function send(Mail $mail)
	{
		echo $mail->generateMessage();
	}

}
