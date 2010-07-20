<?php

class Web_UserPresenter extends Web_BasePresenter {

	public function renderDefault($id) {
		$user = Leganto::users()->getSelector()->find($id);
		$this->getTemplate()->user = $user;
		if ($user == null) {
			$this->flashMessage(System::translate("The user does not exist."), "error");
			$this->redirect("Default:default");
		}
		$this->setPageTitle($user->nickname . ": " . System::translate("Profile"));
	}

	public function renderOpinions($id) {
		$user = Leganto::users()->getSelector()->find($id);
		$this->getTemplate()->user = $user;
		if ($user == null) {
			$this->flashMessage(System::translate("The user does not exist."), "error");
			$this->redirect("Default:default");
		}
		$this->getComponent("opinionList")->setSource(
				Leganto::opinions()->getSelector()
				->findAllByUser($user)
		);
		$this->setPageTitle($user->nickname . ": " . System::translate("Opinions"));
	}

	public function renderToogleFollow($id) {
		if (!Environment::getUser()->isAuthenticated()) {
			$this->redirect("Default:unauthorized");
		} else {
			$id = $id["user"];
			if($id == System::user()->getId()) {
				$this->flashMessage(System::translate("You cannot follow yourself, you egoist!"));
			} else {
				$result = Leganto::users()->getUpdater()->toogleFollow($id);
				if($result == TRUE) {
					$this->flashMessage(System::translate("This user is now followed by you."));
				} else {
					$this->flashMessage(System::translate("This user is no longer followed by you."));
				}
			}
			$this->redirect("default",$id);
		}
	}

	public function renderFollowing($id) {
		$user = Leganto::users()->getSelector()->find($id);
		$this->getTemplate()->user = $user;
		if ($user == null) {
			$this->flashMessage(System::translate("The user does not exist."), "error");
			$this->redirect("Default:default");
		}
		$this->getComponent("userList")->setSource(
				Leganto::users()->getSelector()
				->findAllFollowed($user)
		);
		$this->setPageTitle($user->nickname . ": " . System::translate("Following"));
	}

	public function renderFollowers($id) {
		$user = Leganto::users()->getSelector()->find($id);
		$this->getTemplate()->user = $user;
		if ($user == null) {
			$this->flashMessage(System::translate("The user does not exist."), "error");
			$this->redirect("Default:default");
		}
		$this->getComponent("userList")->setSource(
				Leganto::users()->getSelector()
				->findAllFollowing($user)
		);
		$this->setPageTitle($user->nickname . ": " . System::translate("Followers"));
	}

	public function renderShelves($id) {
		$user = Leganto::users()->getSelector()->find($id);
		$this->getTemplate()->user = $user;
		if ($user == null) {
			$this->flashMessage(System::translate("The user does not exist."), "error");
			$this->redirect("Default:default");
		}
		$this->getTemplate()->shelves = Leganto::shelves()->fetchAndCreateAll(Leganto::shelves()->getSelector()->findByUser($user));
		$this->getTemplate()->books = Leganto::books()->getSelector()->findAllInShelvesByUser($user)->fetchAssoc("id_shelf,id_book");
		$this->setPageTitle($user->nickname . ": " . System::translate("Shelves"));
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

	protected function createComponentSubmenu($name) {
		$submenu = new SubmenuComponent($this, $name);
		$submenu->addLink("default", System::translate("General info"), array("user" => $this->getTemplate()->user->getId()));
		$submenu->addLink("opinions", System::translate("Opinions"), array("user" => $this->getTemplate()->user->getId()));
		$submenu->addLink("shelves", System::translate("Shelves"), array("user" => $this->getTemplate()->user->getId()));
		$submenu->addLink("following", System::translate("Following"), array("user" => $this->getTemplate()->user->getId()));
		$submenu->addLink("followers", System::translate("Followers"), array("user" => $this->getTemplate()->user->getId()));
		if (Environment::getUser()->isAuthenticated() && System::user()->getId() != $this->getTemplate()->user->getId()) {
			if(Leganto::users()->getSelector()->isFollowedBy($this->getTemplate()->user->getId(),System::user())) {
				$submenu->addEvent("toogleFollow", System::translate("Unfollow"), array("user" => $this->getTemplate()->user->getId()));
			} else {
				$submenu->addEvent("toogleFollow", System::translate("Follow"), array("user" => $this->getTemplate()->user->getId()));
			}
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

}
