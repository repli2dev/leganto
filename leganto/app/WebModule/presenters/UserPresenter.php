<?php
class Web_UserPresenter extends Web_BasePresenter
{

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

    // COMPONENTS

    protected function createComponentSubmenu($name) {
	$submenu = new SubmenuComponent($this, $name);
        $submenu->addLink("default", System::translate("General info"), array("user" => $this->getTemplate()->user->getId()));
        $submenu->addLink("opinions", System::translate("Opinions"), array("user" => $this->getTemplate()->user->getId()));
        $submenu->addLink("shelves", System::translate("Shelves"), array("user" => $this->getTemplate()->user->getId()));
        $submenu->addLink("following", System::translate("Following"), array("user" => $this->getTemplate()->user->getId()));
        $submenu->addLink("following", System::translate("Followers"), array("user" => $this->getTemplate()->user->getId()));
	return $submenu;
    }

    protected function createComponentOpinionList($name) {
	$list = new OpinionListComponent($this, $name);
        $list->showBookInfo();
        return $list;
    }

}
