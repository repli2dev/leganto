<?php



abstract class BasePresenter extends Presenter
{
	public $oldLayoutMode = FALSE;


	protected function beforeRender()
	{
		$user = Environment::getUser();
		$this->template->user = $user->isLoggedIn() ? $user->getIdentity() : NULL;
	}

}
