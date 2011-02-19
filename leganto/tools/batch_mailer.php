<?php
// Batch mailer
// Please ensure that database is development/production
// Only use with correct mailserver
// Usage: (from command line only) php batch_mailer.php (use offset from session)
//        for hard setting of offset run php batch_mailer.php OFFSET
// OTESTOVAT
        
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

// Put subject and text here
echo "Preparing mail...\n";
$mail = new Mail();
$mail->setFrom(Environment::getConfig("mail")->info,'Leganto tým');
$mail->setSubject("Čtenáři - Leganto");
$mail->setBody("Testovací text\n");

// Get offset from session
$batchMailer = Environment::getSession('batchMailer');
if(!isSet($batchMailer->offset)) {
	$batchMailer->offset = 0;
}
if(isSet($argv[1]) && is_numeric($argv[1])) {
	$batchMailer->offset = $argv[1];
	echo "New offset was set to ".$batchMailer->offset.".\n";
	echo "Now, start script again!\n";
	die;
}

// Select users
echo "Selecting users from <".($batchMailer->offset).",".($batchMailer->offset+BATCH_LIMIT).")...\n";
$users = dibi::query("SELECT * FROM [user] WHERE 1 LIMIT %i,%i",$batchMailer->offset,BATCH_LIMIT);
if(count($users) == 0) {
	$batchMailer->offset = 0;
	echo "Sending done!\n";
	die;
}
$failed = 0;
foreach ($users as $user) {
	$temp = clone $mail;
	echo "Sending mail to ".$user->email."...";
	try {
		$temp->addTo($user->email,$user->nick);
	} catch (InvalidArgumentException $e) {
		echo " FAILED\n";
		$failed +=1;
	}
	//$temp->send();
	unset($temp); // paranoia
	echo " DONE\n";
}
$batchMailer->offset += BATCH_LIMIT;
if($failed != 0) {
	echo "Failed: ".$failed."\n";
}
echo "Next batch will start at ".($batchMailer->offset)."...\n";