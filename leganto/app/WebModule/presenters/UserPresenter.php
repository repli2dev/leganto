<?php

class Web_UserPresenter extends Web_BasePresenter {

	private $user;

	public function renderDefault($user) {
		$this->getTemplate()->user = $this->getUserEntity();
		if ($this->getUserEntity() == null) {
			$this->flashMessage(System::translate("The user does not exist."), "error");
			$this->redirect("Default:default");
		}
		$this->setPageTitle($this->getUserEntity()->nickname . ": " . System::translate("Profile"));
	}

	public function renderOpinions($user) {
		$this->getTemplate()->user = $this->getUserEntity();
		if ($this->getUserEntity() == null) {
			$this->flashMessage(System::translate("The user does not exist."), "error");
			$this->redirect("Default:default");
		}
		$this->getComponent("opinionList")->setSource(
				Leganto::opinions()->getSelector()
				->findAllByUser($this->getUserEntity())
		);
		$this->setPageTitle($this->getUserEntity()->nickname . ": " . System::translate("Opinions"));
	}

	public function renderToogleFollow($user) {
		if (!Environment::getUser()->isAuthenticated()) {
			$this->redirect("Default:unauthorized");
		} else {
			if($this->getUserEntity()->getId() == System::user()->getId()) {
				$this->flashMessage(System::translate("You cannot follow yourself, you egoist!"));
			} else {
				$result = Leganto::users()->getUpdater()->toogleFollow($this->getUserEntity()->getId());
				if($result == TRUE) {
					$this->flashMessage(System::translate("This user is now followed by you."));
				} else {
					$this->flashMessage(System::translate("This user is no longer followed by you."));
				}
			}
			$this->redirect("default",$id);
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
		$this->setPageTitle($this->getUserEntity()->nickname . ": " . System::translate("Following"));
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
		$this->setPageTitle($this->getUserEntity()->nickname . ": " . System::translate("Followers"));
	}

	public function renderEditShelf($user, $shelf) {
	    if ($this->getUserEntity()->getId() != System::user()->getId()) {
		$this->flashMessage(System::translate("The authenticated user has to insert shelf with his id."), "error");
		$this->redirect("default", System::user()->getId());
	    }
	    $shelfEntity = Leganto::shelves()->getSelector()->find($shelf);
	    if (empty($shelfEntity)) {
		$this->flashMessage(System::translate("The shelf does not exist."), "error");
		$this->redirect("default");
	    }
	    $this->getComponent("insertingShelf")->setShelf($shelfEntity);
	    $this->setPageTitle($this->getUserEntity()->nickname . ": " . System::translate("Edit shelf"));
	}

	public function renderInsertShelf($user, $backlinkUri = NULL) {
	    if ($this->getUserEntity()->getId() != System::user()->getId()) {
		$this->flashMessage(System::translate("The authenticated user has to insert shelf with his id."), "error");
		$this->redirect("default", System::user()->getId());
	    }
	    if (!empty($backlinkUri)) {
		$this->getComponent("insertingShelf")->setBacklink($backlinkUri);
	    }
	    $this->setPageTitle($this->getUserEntity()->nickname . ": " . System::translate("Insert a new shelf"));
	}

	public function renderShelves($user) {
	    $this->getTemplate()->user = $this->getUserEntity();
	    $this->setPageTitle($this->getUserEntity()->nickname . ": " . System::translate("Shelves"));
	}

	public function renderIcon($id) {
		if(empty($id)) {
			$this->redirect("Default:");
		}
		// Fetch data
		$user = Leganto::users()->getSelector()->find($id);
		$data = Leganto::opinions()->getSelector()->findAllByUser(
				$user
			)->orderBy("updated","DESC")->applyLimit(3)->fetchAll();
		$books = array();
		foreach($data as $row) {
			$books[] = Leganto::books()->getSelector()->find($row->id_book_title);
		}
		// Create image and fill it with data
		$image = imagecreatefrompng(WWW_DIR. "/img/propag/big_skeleton.png");
		$black = imagecolorallocate($image,0,0,0);
                $brown = imagecolorallocate($image,51,102,153);
		$font = WWW_DIR."/img/fonts/DejaVuSansMono.ttf";
		imagettftext($image, 9, 0, 38, 31, $black, $font, $user->nickname);
		// First book
		imagettftext($image, 8, 0, 4, 50, $brown, $font, ExtraString::hardTruncate($books[0]->title,20));
		$author = Leganto::authors()->getSelector()->findAllByBook($books[0])->fetchAll();
		$authorInsert = $author[0]->full_name;
		if(count($author) != 1) {
			$authorInsert = $authorInsert."\xE2\x80\xA6";
			$authorInsert = ExtraString::hardTruncate($authorInsert,18,"");
		} else {
			$authorInsert = ExtraString::hardTruncate($authorInsert,18);
		}
                imagettftext($image, 8, 0, 4, 62, $black, $font, $authorInsert);
                
                // Second book
                imagettftext($image, 8, 0, 4, 76, $brown, $font, ExtraString::hardTruncate($books[1]->title,20));
		$author = Leganto::authors()->getSelector()->findAllByBook($books[1])->fetchAll();
		$authorInsert = $author[0]->full_name;
		if(count($author) != 1) {
			$authorInsert = $authorInsert."\xE2\x80\xA6";
			$authorInsert = ExtraString::hardTruncate($authorInsert,18,"");
		} else {
			$authorInsert = ExtraString::hardTruncate($authorInsert,18);
		}
                imagettftext($image, 8, 0, 4, 88, $black, $font, $authorInsert);

		// Third book
		imagettftext($image, 8, 0, 4, 102, $brown, $font, ExtraString::hardTruncate($books[2]->title,20));
		$author = Leganto::authors()->getSelector()->findAllByBook($books[2])->fetchAll();
		$authorInsert = $author[0]->full_name;
		if(count($author) != 1) {
			$authorInsert = $authorInsert."\xE2\x80\xA6";
			$authorInsert = ExtraString::hardTruncate($authorInsert,12,"");
		} else {
			$authorInsert = ExtraString::hardTruncate($authorInsert,12);
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

	protected function createComponentShelves($name) {
	    $shelves = new ShelvesComponent($this, $name);
	    $shelves->setUser($this->getUserEntity());
	    return $shelves;
	}

	protected function createComponentSubmenu($name) {
		$submenu = new SubmenuComponent($this, $name);
		$submenu->addLink("default", System::translate("General info"), $this->getUserEntity()->getId());
		$submenu->addLink("opinions", System::translate("Opinions"), $this->getUserEntity()->getId());
		$submenu->addLink("shelves", System::translate("Shelves"), $this->getUserEntity()->getId());
		$submenu->addLink("following", System::translate("Following"), $this->getUserEntity()->getId());
		$submenu->addLink("followers", System::translate("Followers"), $this->getUserEntity()->getId());
		if (Environment::getUser()->isAuthenticated() && System::user()->getId() != $this->getUserEntity()->getId()) {
			if(Leganto::users()->getSelector()->isFollowedBy($this->getTemplate()->user->getId(),System::user())) {
				$submenu->addEvent("toogleFollow", System::translate("Unfollow"), $this->getUserEntity()->getId());
			} else {
				$submenu->addEvent("toogleFollow", System::translate("Follow"), $this->getUserEntity()->getId());
			}
		}
		if (Environment::getUser()->isAuthenticated() && System::user()->getId() == $this->getUserEntity()->getId()) {
		    $submenu->addEvent("insertShelf", System::translate("Insert a new shelf"), $this->getUserEntity()->getId());
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