<?php
class MessageInfo extends Div {
	
	public function __construct($message) {
		parent::__construct();
		$this->setClass("opinion");
		$info = new Div();
		$info->setClass("info");
		$date = new Span(new String(String::dateFormat($message->date)));
		$date->setClass("date");
		$info->addValue($date);
		unset($date);
		$admin = new A(
			Lng::DELETE,
			"message.php?action=messageDestroy&amp;message=".$message->mesID
		);
		$admin->setClass("admin");
		$info->addValue($admin);
		unset($admin);
		$author = new Span();
		$author->setClass("author");
		$author->addValue(new A(
			$message->userNameFrom,
			"user.php?user=".$message->userIDFrom
		));
		$author->addValue(new String(" » "));
		$author->addValue(new A(
			$message->userNameTo,
			"user.php?user=".$message->userIDTo
		));
		$info->addValue($author);
		unset($author);
		$this->addValue($info);
		unset($info);
		$this->addValue(new String($message->content,TRUE));		
	}
}
?>