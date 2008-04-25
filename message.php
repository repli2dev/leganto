<?php
require("./include/config.php");

switch ($_GET["action"]) {
	case "sendForm":
		$temp->header($lng->sendMessage);
		$temp->menu();
		$temp->messageForm($_GET[userName]);
		$temp->messageRead();
		$temp->middle();
		$temp->tagListTop();
		$temp->footer();
	break;
	case "send":
		$temp->header($lng->sendMessage);
		$temp->menu();
		$message = new message;
		$message->send($_POST[userName],$_POST[mesText]);
		unset($message);
		$temp->messageForm($_GET[userName]);
		$temp->messageRead();
		$temp->middle();
		$temp->tagListTop();
		$temp->footer();		
	break;
	case "destroy":
		$temp->header($lng->sendMessage);
		$temp->menu();
		$message = new message;
		$message->destroy($_GET[message]);		
		$temp->messageForm($_GET[userName]);
		$temp->messageRead();
		$temp->middle();
		$temp->tagListTop();
		$temp->footer();

	break;	
}
?>
