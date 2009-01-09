<?php
require_once("include/config.php");

class UserPage extends CommonPage {
	
	public function __construct() {
		parent::__construct();
		$this->rightColumn->clean();
		$this->addRightColumn(new UserSubMenu());
		$this->addRightColumn(new LastDiscussion());
		$this->addRightColumn(new TagBox());
		switch(Page::get("action")) {
			case "":
				if (Page::get("user")) {
					$owner = Page::session("login");
					if (Page::get("user") != $owner->id) {
						$user = User::getInfo(Page::get("user"));
						$this->setTitle($user->name);
						unset($user);
						$this->addContent(new UserInfo(Page::get("user")));					
					}
					else {
						$this->setTitle($owner->name);
					}
					$this->addContent(new BookListTop(Page::get("user")));
					$this->addContent(new BookListLast(Page::get("user")));
					$this->addContent(new WritingListLast(Page::get("user")));
				}
				else {
					$owner = Page::session("login");
					if (!$owner->id) {
						$this->addContent(new H(2,Lng::REGISTRATION));
						$this->addContent(new FormUser());
					}
					else {
						$this->setTitle($owner->name);
						$this->addContent(new BookListTop($owner->id));
						$this->addContent(new BookListLast($owner->id));
					}
				}
				break;
			case "userForm":
				$this->addContent(new FormUser(Page::get("user")));
				$owner = Page::session("login");
				if ($owner->id) {
					$this->setTitle(Lng::CHANGE_USER_INFO);
					$this->addContent(new FormChangeIco());
					$this->addContent(new FormLibraryMenu());
				}
				else {
					$this->setTitle(Lng::REGISTRATION);
				}
				break;
			case "logOut":
				User::logOut();
				Header("Location: index.php");
			case "allUserBooks":
				$owner = Page::session("login");
				if (Page::get("user") != $owner->id) {
					$this->addContent(new UserInfo(Page::get("user")));					
				}
				$this->addContent(new H("2",Lng::ALL_USER_BOOKS));
				$this->addContent(new BookListUser(Page::get("user")));
				$this->addContent(new Paging(__FILE__));
				$this->setTitle(Lng::ALL_USER_BOOKS);
				break;
			case "search":
				$this->setTitle(Lng::USERS." - ".Lng::SEARCH);
				$this->addContent(new FormUserSearch());
				$this->addContent(new UserTable());
				$this->addContent(new Paging(__FILE__));
				break;
			case "favouriteMake":
				Recommend::create(Page::get("user"));
				Header ("Location: user.php?user=".Page::get("user"));
				break;
			case "favouriteDestroy":
				$owner = Page::session("login");
				Recommend::destroyMine(Page::get("user"));
				Header ("Location: user.php?user=".Page::get("user"));
				break;
			case "userReadlist":
				$owner = Page::session("login");
				if (Page::get("user") != $owner->id) {
					$this->addContent(new UserInfo(Page::get("user")));					
				}
				$this->addContent(new H("2",Lng::BOOKS_TO_READ));
				$this->addContent(new BookReadlistUser(Page::get("user")));
				$this->addContent(new Paging(__FILE__));
				$this->setTitle(Lng::BOOKS_TO_READ);
				break;
		}
	}
}
$page = new UserPage();
$page->view();
?>