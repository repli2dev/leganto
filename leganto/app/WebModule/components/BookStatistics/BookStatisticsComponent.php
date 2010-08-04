<?php
class BookStatisticsComponent extends BaseComponent
{

    private $book;

    public function setBook(BookEntity $book) {
	$this->book = $book;
    }

    protected function beforeRender() {
	$this->getTemplate()->graphs = array();
	$this->getTemplate()->graphs[] = array (
	    "label" => System::translate("Rating of readers"),
	    "graph" => StatisticsGraphs::getRatingsByBook($this->book)
	);
	$this->getTemplate()->graphs[] = array (
	    "label" => System::translate("Sex of readers"),
	    "graph" => StatisticsGraphs::getSexByBook($this->book)
	);
    }

}

