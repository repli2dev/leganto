<?php
// Batch mailer
// Please ensure that database is development/production
// Only use with correct mailserver
// Usage:	(from command line only)
//		php batch_mailer.php			Use OFFSET and TEMPLATE from SESSION
//	        php batch_mailer.php TEMPLATE OFFSET	Set TEMPLATE and OFFSET
        
// Load Nette and set up environment
echo "Mailer running...\n";
require_once(dirname(__FILE__) . "/header.php");
define("BATCH_LIMIT",50);
date_default_timezone_set("Europe/Prague");
Environment::getSession()->setExpiration("+7 days");
session_id("batchmailer"); // HACK
Environment::getSession()->start();

// Database
echo "Database connection...\n";
Environment::loadConfig(APP_DIR . '/config.ini');
$config = Environment::getConfig("database");
dibi::connect($config);

// Get offset from session
$batchMailer = Environment::getSession('batchMailer');

// Set according to parameters
if(count($argv) != 3 && count($argv) != 1) {
	die("Wrong parameter count, please see file header.\n");
}
if(isSet($argv[2]) && is_numeric($argv[2])) {
	$batchMailer->offset = $argv[2];
	echo "New OFFSET was set to ".$batchMailer->offset.".\n";
}
if(isSet($argv[1]) && is_string($argv[1])) {
	// Prepare template
	echo "Preparing mail...\n";
	if(!file_exists($argv[1])) {
		die("File with template doesn't exist!");
	}
	$template = new Template;
	$template->setFile($argv[1]);
	// Prepare mail
	$mail = new Mail();
	$mail->setFrom(Environment::getConfig("mail")->info,'Leganto tým');
	$mail->setSubject("Čtenáři - Leganto");
	$mail->setHtmlBody($template);
	$batchMailer->mail = $mail;
	echo "New template set to ".$argv[1].".\n";
}

// Check if parameters were set (-> end)
if (count($argv) == 3) {
	die("All parameters set, run this script without parameters.\n");
}

// Check if there are present
if(!isSet($batchMailer->offset) || !isSet($batchMailer->mail)) {
	die("OFFSET or TEMPLATE not found, please see file header.\n");
}

// Select users
echo "Selecting users from <".($batchMailer->offset).",".($batchMailer->offset+BATCH_LIMIT).")...\n";
$users = dibi::query("SELECT * FROM [user] WHERE 1 LIMIT %i,%i",$batchMailer->offset,BATCH_LIMIT);
if(count($users) == 0) {
	$batchMailer->remove();
	echo "Sending done!\n";
	die;
}
$failed = 0;
foreach ($users as $user) {
	$temp = clone $batchMailer->mail;
	echo "Sending mail to ".$user->email."...";
	try {
		$temp->addTo($user->email,$user->nick);
		echo " DONE\n";
	} catch (InvalidArgumentException $e) {
		echo " FAILED\n";
		$failed +=1;
	}
	$temp->send();
	unset($temp); // paranoia
}
$batchMailer->offset += BATCH_LIMIT;
if($failed != 0) {
	echo "Failed: ".$failed."\n";
}
echo "Next batch will start at ".($batchMailer->offset)."...\n";