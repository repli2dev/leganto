<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Uvod.
* @package readerTemplate
*/
class Introduction extends Div {
	
	public function __construct() {
		parent::__construct();
		$this->addValue(new String("
<div class=\"box\">
	<h2>Jste tady poprvé?</h2>	
	<p>
		Právě se nacházíte na stránkách <a href=\"http://ctenari.cz\" title=\"Čtenáři.cz - Internetový čtenářský deník\">Internetového čtenářského deníku</a>, který Vám umožní sdílet názory na knihy s ostatními a nacházet literaturu podle Vašeho vkusu.
		Podívejte se, co čte Váš soused, kamarád a další lidé z Vašeho okolí i neokolí.
	</p>
	<ul>
		<li><a href=\"user.php?action=userForm\" title=\"Zaregistrujte se\">Zaregistrujte se</a></li>
		<li>Přidejte první knihu do svého čtenářského deníku</li>
		<li>Podívejte se, co čtou ostatní</li>
		<li>Najděte knihu, kterou znáte, a vyhledejte jí podobnou</li>	
		<li><strong>Řekněte o stránkách svým známým</strong></li>
		<li>Dejte vědět na svých stránkách o tom, <a href=\"about.php#6b\" title=\"Ikonka čtenáře\">co čtete</a>.
	</ul>
</div>
		"));
	}
}
?>