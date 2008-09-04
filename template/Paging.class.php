<?php
class Paging extends Div {
	
	/**
	 * Soubor, ze ktereho je trida volana.
	 * @param string
	 * @return void
	 */
	public function __construct($file) {
		parent::__construct();
		$this->setID("page");;
		$site = "?";
		foreach(Page::get() AS $key => $item) {
			if ($key != "page") {
				if ($site != "?") {
					$site .= "&amp;";
				}
				$site .= "$key=$item";
			}
			
		}
		if($site != "?") {
			$site .= "&amp;";
		}
		$site = basename($file).$site;
		if (Page::get("page")) {
			$link = new A(Lng::FORWARD,$site."page=".(Page::get("page")-1));
			$link->setID("backward");
			$this->addValue($link);
		}
			$link = new A(Lng::BACKWARD,$site."page=".(Page::get("page")+1));
			$link->setID("forward");
			$this->addValue($link);
	}
}
?>