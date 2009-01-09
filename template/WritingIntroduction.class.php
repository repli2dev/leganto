<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Uvod pro literarni souteze.
* @package readerTemplate
*/
class WritingIntroduction extends Div {
	
	public function __construct() {
		parent::__construct();
		$this->addValue(new String("
<div class=\"box\">
	<h2>Nejnovější ve vlastní tvorbě</h2>
	<p>
	Vítejte na přehledu nejnovějších zápisků z vlastní tvorby čtenářů.
	</p>
</div>
		"));
	}
}
?>