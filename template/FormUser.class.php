<?php
class FormUser extends Form {

	protected $isUpdating = FALSE;
	
	/*
	 * Konstruktor.
	 * @param int ID uzivatele
	 * @return void
	 */
	public function __construct($id = NULL) {
		if ($id) {
			$this->isUpdating = TRUE;
		}
		parent::__construct("userForm","user.php?action=userForm&amp;user=$id","post",$id);
		
	}
	
	public function renderForm($name,$action,$method,$enctype) {
		parent::renderForm($name,$action,$method,$enctype);
		if ($this->isUpdating) {
			$legend = Lng::CHANGE_USER_INFO;
			$submitValue = Lng::CHANGE_USER_INFO;
			$nameDisabled = TRUE;
			$importantPassword = FALSE;
		}
		else {
			$legend = Lng::REGISTRATION;
			$submitValue = Lng::REGISTRATE;
			$nameDisabled = FALSE;
			$importantPassword = TRUE;
		}
		$this->addFieldset($legend); 
		$this->addTextInput(TRUE,"userName",Lng::NAME.":",NULL,$nameDisabled);
		$this->addPasswordInput($importantPassword,"password",Lng::PASSWORD.":");
		$this->addPasswordInput($importantPassword,"password_control",Lng::PASSWORD_CONTROL.":");
		$this->addTextInput(TRUE,"email",Lng::EMAIL.":");
		$this->addTextarea(FALSE,"description",Lng::USER_DESCRIPTION);
		$this->addSubmitButton("userFormSubmitButton",$submitValue);
	}
	
	protected function isSend() {
		if (Page::post("userFormSubmitButton")) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
	protected function getDataToFill($id) {
		if ($this->isUpdating) {
			$owner = Page::session("login");
			return array(
				"userName" => $owner->name,
				"email" => $owner->email,
				"description" => $owner->description
			);
		}
		else {
			return array();
		}
	}
	
	protected function execute() {
		if (Page::get("user")) {
			$change = array(
				"name" => Page::post("userName"),
				"email" => Page::post("email"),
				"description" => Page::post("description"),
			);
			if (Page::post("password")) {
				$change["password"] = Page::post("password");
				$change["password_control"] = Page::post("password_control");
			}
			User::change(Page::get("user"),$change);
			$owner = Page::session("login");
			Page::setSession("login",User::getInfo($owner->id));
			Page::loadSession();
			Header("Location: user.php");
		}
		else {
			User::create(array(
				"name" => Page::post("userName"),
				"email" => Page::post("email"),
				"description" => Page::post("description"),
				"password" => Page::post("password"),
				"password_control" => Page::post("password_control")
			));
			Header("Location: user.php");
		}
	}
}
