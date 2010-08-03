<?php

class Web_StatisticsPresenter extends Web_BasePresenter
{

    public function renderDefault() {
	$this->getTemplate()->opinionsByRating	= StatisticsGraphs::getNumberOfOpinionsByRating()->getLink();
	$this->getTemplate()->opinionsLastYear	= StatisticsGraphs::getNumberOfInsertedOpinionsLastYear()->getLink();
	$this->setPageTitle(System::translate("Statistics"));
    }

}
