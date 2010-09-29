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
class Web_StatisticsPresenter extends Web_BasePresenter {

	public function renderDefault() {
		$this->getTemplate()->opinionsByRating = StatisticsGraphs::getNumberOfOpinionsByRating()->getLink();
		$this->getTemplate()->opinionsLastYear = StatisticsGraphs::getNumberOfInsertedOpinionsLastYear()->getLink();
		$this->setPageTitle(System::translate("Statistics"));
		$this->setPageDescription(System::translate("How many books, opinions, users there are on our web? How fast we grow? This page will answer you!"));
		$this->setPageKeywords(System::translate("statistics, graphs, number of books, number of opinions, number of users"));
	}

}
