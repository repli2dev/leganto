<?php

class Web_StatisticsPresenter extends Web_BasePresenter {

	public function renderDefault() {
		$this->getTemplate()->opinionsByRating = StatisticsGraphs::getNumberOfOpinionsByRating()->getLink();
		$this->getTemplate()->opinionsLastYear = StatisticsGraphs::getNumberOfInsertedOpinionsLastYear()->getLink();
		$this->setPageTitle(System::translate("Statistics"));
		$this->setPageDescription(System::translate("How many books, opinions, users there are on our web? How fast we grow? This page will answer you!"));
		$this->setPageKeywords(System::translate("statistics, graphs, number of books, number of opinions, number of users"));
	}

}
