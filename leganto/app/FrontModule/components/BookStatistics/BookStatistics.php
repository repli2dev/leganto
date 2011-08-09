<?php
/**
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
namespace FrontModule\Components;
use Leganto\System,
	Leganto\DB\Statistic\StatisticsGraphs;
	
	
class BookStatistics extends BaseComponent {

	private $book;

	public function setBook(\Leganto\DB\Book\Entity $book) {
		$this->book = $book;
	}

	protected function beforeRender() {
		$this->getTemplate()->graphs = array();
		$this->getTemplate()->graphs[] = array(
		    "label" => System::translate("Rating of readers"),
		    "graph" => StatisticsGraphs::getRatingsByBook($this->book)
		);
		$this->getTemplate()->graphs[] = array(
		    "label" => System::translate("Sex of readers"),
		    "graph" => StatisticsGraphs::getSexByBook($this->book)
		);
	}

}

