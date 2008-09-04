<?php
class SimilarUsersBox extends Column {

	private $switcherView = FALSE;
	
	public function __construct() {
		parent::__construct();
		$this->addValue(new H(2,new String(Lng::SIMILAR_USERS)));
		$ul = new Ul();
		$res = User::getSimilar();
		while ($user = mysql_fetch_object($res)) {
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