<?php
class UserIconComponent extends BaseComponent
{

	private $user;

	public function render() {
		if (Environment::getUser()->isAuthenticated() && System::user()->getId() == $this->user->getId()) {
			return parent::render();
		}
	}

	public function setUser(UserEntity $user) {
		$this->user = $user;
	}

}
