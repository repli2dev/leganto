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
