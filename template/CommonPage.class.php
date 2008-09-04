<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/

/**
* Tato trida slouzi jako predek pro zobrazovane stranky.
* @package reader
*/
class CommonPage extends Page {
	
	/**
	 * @var mixed Obsah (ta vetsi cast) stranky.
	 */
	protected $content;
	
	/**
	 * @var Div Levy sloupec.
	 */
	protected $leftColumn;
	
	/**
	 * @var Div Pravy sloupec.
	 */
	protected $rightColumn;
	
	public function __construct() {
		parent::__construct();
	
		$this->setTitle(Lng::THIS_SITE);
		
		$this->addStyleSheet("main.css");
		
		$this->leftColumn = new Div;
		$this->leftColumn->setID("left");
		
		$this->rightColumn = new Div();
		$this->rightColumn->setID("right");
		
		$this->content = new Div;
		$this->content->setID("content");
		
		$owner = Page::session("login");
		if (empty($owner->id) && (!User::logInIfRemebered())) {
			$this->addLeftColumn(new Column(new FormLogIn()));	
		}
		else {
			$this->addLeftColumn(new UserInfo());
			$this->addLeftColumn(new RecommendedUsersBox());
			$this->addLeftColumn(new BookByFavourite());
			$this->addLeftColumn(new SimilarUsersBox());
		}
		$this->addRightColumn(new LastCommentedBooks());
		$this->addRightColumn(new LastDiscussion());
		$this->addRightColumn(new TagBox());
	}
	
	/**
	 * Změni titulek stranky.
	 */
	public function setTitle($title) {
		parent::setTitle("Čtenáři.cz - ".$title);
	}
	
	/**
	 * Prida polozku do obsahu.
	 * @param Object Polozka, kterou chci do obsahu pridat.
	 * @return void
	 */
	public function addContent($value) {
		$this->content->addValue($value);
	}

	/**
	 * Prida polozku do leveho sloupce.
	 * @param Object Polozka, kterou chci do menu pridat.
	 * @return void
	 */
	public function addLeftColumn($value) {
		$this->leftColumn->addValue($value);
	}
	
	/**
	 * Prida polozku do praveho sloupce.
	 * @param Object Polozka, kterou chci do menu pridat.
	 * @return void
	 */
	public function addRightColumn($value) {
		$this->rightColumn->addValue($value);
	}
	
	public function view() {
		$background = new Div;
		$background->setID("background");
		$background->addValue(new Header());
		$body = new Div();
		$body->setID("body");
		$body->addValue($this->leftColumn);
		unset($this->leftColumn);
		$body->addValue($this->rightColumn);
		unset($this->rightColumn);
		$this->content->addValue(new P(new A(
			Lng::UP,
			"#head"
		)));
		$body->addValue($this->content);
		unset($this->content);
		$background->addValue($body);
		unset($body);
		$background->addValue(new Footer());
		$this->addValue($background);
		unset($background);
		parent::view();
	}
}
?>