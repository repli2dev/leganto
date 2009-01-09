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
class CompetitionIntroduction extends Div {
	
	public function __construct() {
		parent::__construct();
		$this->addValue(new String("
<div class=\"box\">
	<h2>Literární soutěže</h2>
	<p>
	Vítejte na rozcestníku literárních soutěží. 
	</p>
</div>
		"));
	}
}
?>