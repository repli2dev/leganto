<?php
class UserBoxTop extends Column {
	
	public function __construct() {
		parent::__construct();
		$this->addValue(new H(2,new String(Lng::TOP_USER)));
		$res = User::topList();
		$p = new P();
		while ($user = mysql_fetch_object($res)) {
			$p->addValue(new A(
				$user->name,
				"user.php?user=".$user->id
			));
		}
		$this->addValue($p);
		unset($p);
	}
}
?>