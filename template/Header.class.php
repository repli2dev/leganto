<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/

/**
* Hlavicka stranky.
* @package reader
*/
class Header extends Div {
	
	public function __construct() {
		parent::__construct();
		
		$this->setID("header");
		
		// Logo v hlavicce obsahujici nadpis (url stranky).
		$logo = new Div();
		$logo->setID("head");
		$this->addValue($logo);
		unset($logo);
		
		// Menu v hlavicce
		$menu = new Div();
		$menu->setID("navigation");
		$left = new Div();
		$left->setID("navigation-left");
		$menu->addValue($left);
		unset($left);
		$right = new Div();
		$right->setID("navigation-right");
		$menu->addValue($right);
		unset($right);
		$section = new H(1,new A(
			Lng::READER_BOOK,
			"index.php"
		));
		$section->setID("section");
		$menu->addValue($section);
		unset($section);
		$menuUl = new Ul();
		$menuUl->setID("links");
		$owner = Page::session(login);
		switch($owner->level) {
			case User::LEVEL_ADMIN:
				$menuUl->addLi(new A(Lng::ADMIN,"admin.php"));
			case User::LEVEL_MODERATOR:
				$menuUl->addLi(new A(Lng::MANAGMENT,"moderator.php"));
			case User::LEVEL_COMMON:
				$menuUl->addLi(new A(Lng::ADD_BOOK,"book.php?action=formBook"));
			break;
			default:
				$menuUl->addLi(new A(Lng::REGISTRATE,"user.php?action=userForm"));
		}
		$menuUl->addLi(new A(Lng::USERS,"user.php?action=search"));
		$menuUl->addLi(new A(Lng::DISCUSSION,"discussion.php"));
		$menuUl->addLi(new A(Lng::ABOUT,"about.php"));
		$menu->addValue($menuUl);
		$menu->addValue(new FormSearch());
		unset($form);
		$this->addValue($menu);
		
	}
}
?>