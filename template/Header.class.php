<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Hlavicka stranky.
* @package readerTemplate
*/
class Header extends Div {
	
	/**
	 * @param string
	 */
	public function __construct($pageType = NULL) {
		parent::__construct();
		
		if ($pageType == "competition") {
			$commonLink = new A(Lng::ADD_COMPETITION,"competition.php?action=formCompetition");
			$sectionName = Lng::COMPETITIONS;
			$sectionURL = "competition.php";
		}
		else
		if($pageType == "writing"){
			$commonLink = new A(Lng::ADD_WRITING,"writing.php?action=addWriting");
			$sectionName = Lng::WRITINGS;
			$sectionURL = "writing.php";
		}
		else {
			$commonLink = new A(Lng::ADD_BOOK,"book.php?action=formBook");
			$sectionName = Lng::READER_BOOK;
			$sectionURL = "index.php";
		}
		
		$this->setID("header");
		
		// Logo v hlavicce obsahujici nadpis (url stranky).
		$logo = new Div();
		$logo->setID("head");
		$section = new Div();
		$section->setID("section-links");
		//Ctenarsky denik
		$link = new A(Lng::READER_BOOK,"/");
		$link->setClass("main-link");
		$section->addValue($link);
		//Literarni souteze
		$link = new A(Lng::COMPETITIONS,"competition.php");
		$link->setClass("competition-link");
		$section->addValue($link);
		//Vlastní tvorba
		$link = new A(Lng::WRITINGS,"writing.php");
		$link->setClass("writing-link");
		$section->addValue($link);
		unset($link);
		$logo->addValue($section);
		unset($section);
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
			$sectionName,
			$sectionURL
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
				$menuUl->addLi($commonLink);
				unset($commonLink);
			break;
			default:
				$menuUl->addLi(new A(Lng::REGISTRATE,"user.php?action=userForm"));
		}
		$menuUl->addLi(new A(Lng::USERS,"user.php?action=search"));
		$menuUl->addLi(new A(Lng::DISCUSSION,"discussion.php"));
		$menuUl->addLi(new A(Lng::ABOUT,"about.php"));
		$menu->addValue($menuUl);
		$menu->addValue(new FormSearch($pageType));
		unset($form);
		$this->addValue($menu);
		
	}
}
?>
