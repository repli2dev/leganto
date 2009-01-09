<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Box se soukromou zpravou.
* @package readerTemplate
*/
class MessageInfo extends Div {
	
	public function __construct($message) {
		parent::__construct();
		$this->setClass("opinion");
		if($message->isRead == 1){
			$this->setClass("unread");
		}
		$info = new Div();
		$info->setClass("info");
		$date = new Span(new String(String::dateFormat($message->date)));
        Page::addJsFile("helper()");
        $answer = new A(
			" ".Lng::ANSWER,
			"javascript:onClick_fillin_username('".$message->userNameFrom."')"
		);
        //zobrazit odkaz Reagovat pouze pokud neni uzivatel autor zpravy
        $owner = Page::session("login");
        if($owner->id != $message->userIDFrom){
            $date->addValue($answer);
            $info->addValue(new String("&raquo;"));
        } else {
            $info->addValue(new String("&laquo;"));
        }
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