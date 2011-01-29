<?php

/**
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
class Web_UserPresenter extends Web_BasePresenter {

	private $user;

	public function renderDefault($user) {
		$this->getTemplate()->user = $this->getUserEntity();
		if ($this->getUserEntity() == null) {
			$this->flashMessage(System::translate("The user does not exist."), "error");
			$this->redirect("Default:default");
		}
		$source = Leganto::opinions()->getSelector()->findAllByUser($this->getUserEntity());
		$this->getComponent("opinionList")->setSource($source);
		// Set stats
		$this->getTemplate()->numOfBooks = $source->count();
		// TODO: zpusobovalo chybu
		// $this->getTemplate()->numOfOpinions = $source->where("content != ''")->count();
		$this->getTemplate()->numOfPosts = Leganto::posts()->getSelector()->findAll()->where("id_user = %i",$this->getUserEntity()->getId())->count();
		$this->getTemplate()->numOfShelves = Leganto::shelves()->getSelector()->findAll()->where("id_user = %i",$this->getUserEntity()->getId())->count();
		
		$this->setPageTitle(System::translate("Profile and opinions") . ": " . $this->getUserEntity()->nickname);
		$this->setPageDescription(System::translate("This is the profile page of a user where you can track his or her opinions, look into shelves, find followers and followed users."));
		$this->setPageKeywords(System::translate("followers, following, user profile, user detail, users opinion"));
	}

	public function renderToogleFollow($user) {
		if (!Environment::getUser()->isAuthenticated()) {
			$this->redirect("Default:unauthorized");
		} else {
			if ($this->getUserEntity()->getId() == System::user()->getId()) {
				$this->flashMessage(System::translate("You cannot follow yourself, you egoist!"), "error");
			} else {
				$result = Leganto::users()->getUpdater()->toogleFollow($this->getUserEntity()->getId());
				if ($result == TRUE) {
					System::log("FOLLOW USER '" . $this->getUserEntity()->getId() . "'");
					$this->flashMessage(System::translate("This user is now followed by you."), "success");
				} else {
					System::log("UNFOLLOW USER'" . $this->getUserEntity()->getId() . "'");
					$this->flashMessage(System::translate("This user is no longer followed by you."), "success");
				}
			}
			$this->redirect("default", $this->getUserEntity()->getId());
		}
	}

	public function renderFollowing($user) {
		$this->getTemplate()->user = $this->getUserEntity();
		if ($this->getUserEntity() == null) {
			$this->flashMessage(System::translate("The user does not exist."), "error");
			$this->redirect("Default:default");
		}
		$this->getComponent("userList")->setSource(
				Leganto::users()->getSelector()
				->findAllFollowed($this->getUserEntity())
		);
		$this->setPageTitle(System::translate("Following") . ": " . $this->getUserEntity()->nickname);
		$this->setPageDescription(System::translate("Users who a certain user follows."));
		$this->setPageKeywords(System::translate("followers, following, user profile, user detail"));
	}

	public function renderFollowers($user) {
		$this->getTemplate()->user = $this->getUserEntity();
		if ($this->getUserEntity() == null) {
			$this->flashMessage(System::translate("The user does not exist."), "error");
			$this->redirect("Default:default");
		}
		$this->getComponent("userList")->setSource(
				Leganto::users()->getSelector()
				->findAllFollowing($this->getUserEntity())
		);
		$this->setPageTitle(System::translate("Followers") . ": " . $this->getUserEntity()->nickname);
		$this->setPageDescription(System::translate("Users which follow this user."));
		$this->setPageKeywords(System::translate("followers, following, user profile, user detail"));
	}

	public function renderEditShelf($user, $shelf) {
		if ($this->getUserEntity()->getId() != System::user()->getId()) {
			$this->flashMessage(System::translate("The authenticated user has to insert a shelf with his id."), "error");
			$this->redirect("default", System::user()->getId());
		}
		$shelfEntity = Leganto::shelves()->getSelector()->find($shelf);
		if (empty($shelfEntity)) {
			$this->flashMessage(System::translate("The shelf does not exist."), "error");
			$this->redirect("default");
		}
		$this->getComponent("insertingShelf")->setShelf($shelfEntity);
		$this->setPageTitle(System::translate("Edit shelf") . ": " . $this->getUserEntity()->nickname);
		$this->setPageDescription(System::translate("You can edit a shelf on this page."));
		$this->setPageKeywords(System::translate("book shelf, edit, update, change"));
	}

	public function renderInsertShelf($user, $backlinkUri = NULL) {
		if (!System::isCurrentlyLogged($this->getUserEntity()->getId())) {
			$this->flashMessage(System::translate("The authenticated user can't insert shelf to other users."), "error");
			$this->redirect("default", System::user()->getId());
		}
		if (!empty($backlinkUri)) {
			$this->getComponent("insertingShelf")->setBacklink($backlinkUri);
		}
		$this->setPageTitle(System::translate("Insert a new shelf") . ": " . $this->getUserEntity()->nickname);
		$this->setPageDescription(System::translate("You can insert a new shelf on this page"));
		$this->setPageKeywords(System::translate("book shelf, insert, new"));
	}

	public function renderShelves($user) {
		$this->getTemplate()->user = $this->getUserEntity();
		$this->setPageTitle(System::translate("Shelves") . ": " . $this->getUserEntity()->nickname);
		$this->setPageDescription(System::translate("You can see shelves of the user and books in them on this page. Owners can re-order their books here."));
		$this->setPageKeywords(System::translate("book shelves, update, reorder, manipulation, books"));
	}

	public function renderSimilar($user) {
		$this->getTemplate()->user = $this->getUserEntity();
		$this->setPageTitle(System::translate("Similar users") . ": " . $this->getUserEntity()->nickname);
		$this->setPageDescription(System::translate("You can see similar users to a specific user on this page. The similarity is computed on the base of books users have read."));
		$this->setPageKeywords(System::translate("user, books, similarity, similar users, similar"));

		$this->getComponent("userList")->setSource(
				Leganto::users()->getSelector()
				->findAllSimilar($this->getUserEntity())
				->applyLimit(12)
		);
	}

	public function renderMessages($toUser = null) {
		if (!Environment::getUser()->isAuthenticated()) {
			$this->redirect("Default:unauthorized");
		} else {
			$this->setPageTitle(System::translate("Private messaging"));
			$this->setPageDescription(System::translate("You can write to other users and change your thought privately. However you have to known the nickname of user you want to write to."));
			$this->setPageKeywords(System::translate("user, private messaging, private message, write to other users, chat, similar users"));
			// For submenu of current logged user
			$this->user = System::user();
			// Fetch data
			$this->getComponent("messageList")->setSource(
				Leganto::messages()->getSelector()
				->findAllWithUser($this->getUserEntity())
			);
			if($toUser != NULL) {
				$this->getComponent("messageList")->setRecipient($toUser);
			}
		}
	}

	public function renderIcon($id) {
		if (empty($id)) {
			$this->redirect("Default:");
		}
		// Fetch data
		$user = Leganto::users()->getSelector()->find($id);
		$data = Leganto::opinions()->getSelector()->findAllByUser(
				$user
			)->orderBy("updated", "DESC")->applyLimit(3)->fetchAll();
		$books = array();
		foreach ($data as $row) {
			$books[] = Leganto::books()->getSelector()->find($row->id_book_title);
		}
		// Create image and fill it with data
		$image = imagecreatefrompng(WWW_DIR . "/img/propag/big_skeleton.png");
		$black = imagecolorallocate($image, 0, 0, 0);
		$brown = imagecolorallocate($image, 51, 102, 153);
		$font = WWW_DIR . "/img/fonts/DejaVuSansMono.ttf";
		imagettftext($image, 9, 0, 38, 31, $black, $font, $user->nickname);
		// First book
		imagettftext($image, 8, 0, 4, 50, $brown, $font, ExtraString::hardTruncate($books[0]->title, 20));
		$author = Leganto::authors()->getSelector()->findAllByBook($books[0])->fetchAll();
		$authorInsert = $author[0]->full_name;
		if (count($author) != 1) {
			$authorInsert = $authorInsert . "\xE2\x80\xA6";
			$authorInsert = ExtraString::hardTruncate($authorInsert, 18, "");
		} else {
			$authorInsert = ExtraString::hardTruncate($authorInsert, 18);
		}
		imagettftext($image, 8, 0, 4, 62, $black, $font, $authorInsert);

		// Second book
		imagettftext($image, 8, 0, 4, 76, $brown, $font, ExtraString::hardTruncate($books[1]->title, 20));
		$author = Leganto::authors()->getSelector()->findAllByBook($books[1])->fetchAll();
		$authorInsert = $author[0]->full_name;
		if (count($author) != 1) {
			$authorInsert = $authorInsert . "\xE2\x80\xA6";
			$authorInsert = ExtraString::hardTruncate($authorInsert, 18, "");
		} else {
			$authorInsert = ExtraString::hardTruncate($authorInsert, 18);
		}
		imagettftext($image, 8, 0, 4, 88, $black, $font, $authorInsert);

		// Third book
		imagettftext($image, 8, 0, 4, 102, $brown, $font, ExtraString::hardTruncate($books[2]->title, 20));
		$author = Leganto::authors()->getSelector()->findAllByBook($books[2])->fetchAll();
		$authorInsert = $author[0]->full_name;
		if (count($author) != 1) {
			$authorInsert = $authorInsert . "\xE2\x80\xA6";
			$authorInsert = ExtraString::hardTruncate($authorInsert, 12, "");
		} else {
			$authorInsert = ExtraString::hardTruncate($authorInsert, 12);
		}
		imagettftext($image, 8, 0, 4, 114, $black, $font, $authorInsert);

		// Send to browser
		header('Content-Type: image/png');
		imagepng($image);
		imagedestroy($image);
		die;
	}

	// COMPONENTS

	protected function createComponentInsertingShelf($name) {
		return new InsertingShelfComponent($this, $name);
	}

	protected function createComponentUserIcon($name) {
		$icon = new UserIconComponent($this, $name);
		$icon->setUser($this->getUserEntity());
		return $icon;
	}

	protected function createComponentShelves($name) {
		$shelves = new ShelvesComponent($this, $name);
		$shelves->setUser($this->getUserEntity());
		return $shelves;
	}

	protected function createComponentSubmenu($name) {
		$submenu = new SubmenuComponent($this, $name);
		$submenu->addLink("default", System::translate("Info and opinions"), $this->getUserEntity()->getId());
		$submenu->addLink("shelves", System::translate("Shelves"), $this->getUserEntity()->getId());
		$submenu->addLink("following", System::translate("Following"), $this->getUserEntity()->getId());
		$submenu->addLink("followers", System::translate("Followers"), $this->getUserEntity()->getId());
		$submenu->addLink("similar", System::translate("Similar users"), $this->getUserEntity()->getId());
		if (Environment::getUser()->isAuthenticated() && System::user()->getId() != $this->getUserEntity()->getId()) {
			if (Leganto::users()->getSelector()->isFollowedBy($this->getTemplate()->user->getId(), System::user())) {
				$submenu->addEvent("toogleFollow", System::translate("Unfollow"), $this->getUserEntity()->getId());
			} else {
				$submenu->addEvent("toogleFollow", System::translate("Follow"), $this->getUserEntity()->getId());
			}
			
			$submenu->addEvent("messages", System::translate("Write message"), $this->getUserEntity()->getId());
		}
		if (Environment::getUser()->isAuthenticated() && System::user()->getId() == $this->getUserEntity()->getId()) {
			$submenu->addEvent("insertShelf", System::translate("Insert a new shelf"), $this->getUserEntity()->getId());
			$submenu->addEvent("messages", System::translate("Messaging"));
		}
		return $submenu;
	}

	protected function createComponentOpinionList($name) {
		$list = new OpinionListComponent($this, $name);
		$list->showBookInfo();
		$list->showSorting();
		return $list;
	}

	protected function createComponentUserList($name) {
		$list = new UserListComponent($this, $name);
		return $list;
	}
	protected function createComponentMessageList($name) {
		return new MessageListComponent($this, $name);
	}

	// PRIVATE METHODS

	private function getUserEntity() {
		if (empty($this->user)) {
			$id = $this->getParam("user");
			if (!empty($id)) {
				$this->user = Leganto::users()->getSelector()->find($this->getParam("user"));
			}
			if (empty($this->user)) {
				$this->flashMessage(System::translate("The user does not exist."), "error");
				$this->redirect("Default:default");
			}
		}
		return $this->user;
	}

}
