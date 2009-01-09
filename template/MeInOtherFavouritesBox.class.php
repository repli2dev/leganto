<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Zobrazi uzivatele, kteri me maji v oblinych.
* @package readerTemplate
*/
class MeInOtherFavouritesBox extends Column {
	
	private $switcherView = FALSE;
	
	public function __construct() {
		parent::__construct();
		$owner = Page::session("login");
		$res = Recommend::meInOthers($owner->id);
		$this->addValue(new H(2,new String(Lng::ME_IN_OTHER_FAVOURITES)));
		if(mysql_num_rows($res) > 0){
			$ul = new Ul();
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
	}
	
	public function view() {
		if ($this->switcherView) {
			parent::view();
		}
	}
}
?>