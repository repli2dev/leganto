<?php
class RecommendedUsersBox extends Column {
	
	private $switcherView = FALSE;
	
	public function __construct() {
		parent::__construct();
		$this->addValue(new H(2,new String(Lng::USER_FAVOURITE)));
		$owner = Page::session("login");
		$res = Recommend::byUser($owner->id);
		$ul = new Ul();
		while($user = mysql_fetch_object($res)) {
			$ul->addLi(new A(
				$user->name,
				"user.php?user=".$user->id
			));
			$this->switcherView = TRUE;
		}
		$this->addValue($ul);
		unset($ul);
	}
	
	public function view() {
		if ($this->switcherView) {
			parent::view();
		}
	}
}
?>