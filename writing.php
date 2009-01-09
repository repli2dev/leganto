<?php
require_once("include/config.php");

class WritingPage extends CommonPage {

	/**
	 * Proměnná, která v sobě drží aktuální hodnotu zanoření při vypisování komentářů
	 * @var int
	 */
	//TODO: Vymyslet to lépe
	var $level = -1;

	public function __construct() {
		parent::__construct();
		$this->header = new Header("writing");
		$this->setTitle(Lng::WRITINGS);
		$this->clearStyleSheet();
		$this->addStyleSheet("writing.css");
		$this->rightColumn->clean();
		$this->addRightColumn(new WritingSubMenu());
		$id = Page::get("id");
		if(isset($id)) $this->addRightColumn(new WritingNextThisUserSubMenu(Page::get("id")));
		$this->addRightColumn(new LastDiscussion());
		$this->addRightColumn(new TagBox("writing"));
		switch(Page::get("action")) {
			case "": //zobrazit poslednich 20 zapisku
				$this->addContent(new WritingIntroduction());
				$res = Writing::makeCommonQuery();
				while($row = mysql_fetch_object($res)){
					$this->addContent(new WritingEntry($row,FALSE));
				}
				$this->addContent(new Paging(__FILE__));
				break;
			case "addWriting": //pridani nebo uprava zapisku autora
				$owner = Page::session("login");
				if (!empty($owner->id)) {
					$this->addContent(new H(2,Lng::WRITINGS));
					$this->addContent(new String(Lng::WRITINGS_WARNING));
					$this->addContent(new FormWriting(Page::get("id")));
				} else {
					$this->setTitle(Lng::ACCESS_DENIED);
					$this->addContent(Lng::ACCESS_DENIED);
				}
				break;
			case "destroyComment":
				if (Discussion::destroy(Page::get("comment"))) {
					Header("Location: writing.php?action=readOne&id=".Page::get("id"));
				}
			case "readOne": //zobrazeni zapisek samotneho
				if (Page::get("id")) {
					$res = Writing::read(Page::get("id"));
					$row = mysql_fetch_object($res);
					$this->addContent(new WritingEntry($row,TRUE));
					$this->addContent(new TagBox("writing",Page::get("id")));
					$this->addContent(new FormTag("writing"));
					//komentare
					$header = new H(2,new String(Lng::COMMENTS));
					$header->setID("comment");
					$this->addContent($header);
					unset($header);
					if(empty($row->link)){
						self::comment(Page::get("id"),0,Page::get("page"));
						$this->addContent(new Paging(__FILE__));
						$this->addContent(new FormWritingComment());
					} else {
						$this->addContent(new P(Lng::WRITING_CANNOT_COMMENT));
					}
				}
				break;
			case "destroyWriting": //smazani zapisku
				$owner = Page::session("login");
				if (Writing::destroy(Page::get("id"))) {
					Header("Location: writing.php");
				}
				break;
			case "userWriting": //zapisky daneho uzivatele
				$user = Page::get("user");
				if(!empty($user)){
					$res = User::userName($user);
					$row = mysql_fetch_object($res);
					$userName = $row->name;
					$this->addContent(new H(2, Lng::WRITING_USER." &ndash; ".$userName));
					$res = Writing::getAllUser($user);
					while($row = mysql_fetch_object($res)){
						$this->addContent(new WritingEntry($row,FALSE));
					}
					$this->addContent(new Paging(__FILE__));
				}
				break;
			case "search":
				$this->addContent(new WritingListSearch());
				break;
		}
	}

	/**
	 * Funkce, ktera rekurzivne projde vlakna diskuze daneho zapisku
	 * @param int ID zapisku
	 * @param int ID rodicovskeho prisevku, od ktereho se ma zacit
	 * @param int strana
	 */
	public function comment($writing,$parent, $page){
		$this->level++;
		$res = Discussion::readResponsable($writing,"writing",$parent,$page);
		while($row = mysql_fetch_object($res)) {
			$this->addContent(new WritingCommentInfo($row,$this->level));
			if(Discussion::isParent($row->id)){
					self::comment($writing,$row->id,$page);
				
			}
		}
		$this->level--;
	}
}
$page = new WritingPage();
$page->view();
?>