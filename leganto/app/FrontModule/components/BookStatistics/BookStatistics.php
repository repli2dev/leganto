<?php

/**
 * Nice charts about books
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace FrontModule\Components;

use Leganto\DB\Statistic\StatisticsGraphs;

class BookStatistics extends BaseComponent {

	private $book;

	/**
	 * Set current book
	 * @param \Leganto\DB\Book\Entity $book 
	 */
	public function setBook(\Leganto\DB\Book\Entity $book) {
		$this->book = $book;
	}

	protected function beforeRender() {
		StatisticsGraphs::setConnection($this->getContext()->getService("database"));
		StatisticsGraphs::setTranslator($this->getContext()->getService("translator")->get());
		$this->getTemplate()->graphs = array();
		$this->getTemplate()->graphs[] = array(
		    "label" => $this->translate("Rating of readers"),
		    "graph" => StatisticsGraphs::getRatingsByBook($this->book)
		);
		$this->getTemplate()->graphs[] = array(
		    "label" => $this->translate("Sex of readers"),
		    "graph" => StatisticsGraphs::getSexByBook($this->book)
		);
	}

}

