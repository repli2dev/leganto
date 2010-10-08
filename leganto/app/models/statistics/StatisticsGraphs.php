<?php

/**
 * Tool for generating nice graphs (using google charts)
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
class StatisticsGraphs {

	/** @return GoogleChart */
	public static function getNumberOfOpinionsByRating() {
		// Load data
		$res = dibi::query("SELECT COUNT(*) AS number, rating FROM opinion GROUP BY rating");
		$statistics = array();
		while ($stat = $res->fetch()) {
			$statistics[$stat->rating] = $stat->number;
		}
		// Build chart
		$chart = new GoogleChart(GoogleChart::TYPE_TWO_DIMENSIONAL_PIE);
		$chart->setAxes(array(GoogleChart::AXES_LEFT, GoogleChart::AXES_BOTTOM));
		$chart->setSize(300, 600);
		$chart->setLegend(array_keys($statistics));
		$chart->setLabels(GoogleChart::AXES_BOTTOM, $statistics);
		// Tango pallete
		$chart->setColors(array("8ae234","3465a4","fce94f","f57900","a40000"));
		$chart->addDataSet($statistics);
		return $chart;
	}

	/** @return GoogleChart */
	public static function getNumberOfInsertedOpinionsLastYear() {
		return self::getNumberOfInsertedItemsLastYear("opinion");
	}

	public static function getRatingsByBook(BookEntity $book) {
		$res = dibi::query("SELECT COUNT([id_opinion]) AS [number], [rating] FROM [opinion] WHERE [id_book_title] = %i", $book->getId(), " GROUP BY [rating] ORDER BY [rating] DESC");
		$data = array();
		while ($stat = $res->fetch()) {
			$data[$stat->rating] = $stat->number;
		}
		if (empty($data)) {
			return;
		}
		$statistics = array();
		for ($i = 5; $i > 0; $i--) {
			if (!isset($data[$i])) {
				$statistics[$i] = 0;
			} else {
				$statistics[$i] = $data[$i];
			}
		}
		// Build chart
		$chart = new GoogleChart(GoogleChart::TYPE_TWO_DIMENSIONAL_PIE);
		$chart->setAxes(array(GoogleChart::AXES_LEFT, GoogleChart::AXES_BOTTOM));
		$chart->setSize(120, 240);
		$chart->setLegend(array_keys($statistics));
		// Tango pallete
		$chart->setColors(array("8ae234","3465a4","fce94f","f57900","a40000"));
		//$chart->setLabels(GoogleChart::AXES_BOTTOM, $statistics);
		$chart->addDataSet($statistics);
		//$chart->setName(System::translate("Ratings"));
		return $chart;
	}

	public static function getSexByBook(BookEntity $book) {
		$res = dibi::query("
	    SELECT
		COUNT([id_user])	    AS [number],
		IFNULL([sex], 'unspecified')    AS [sex]
	    FROM [opinion]
	    INNER JOIN [user] USING([id_user])
	    WHERE [id_book_title] = %i", $book->getId(),
				"GROUP BY [sex]"
		);
		$data = array();
		while ($stat = $res->fetch()) {
			$data[$stat->sex] = $stat->number;
		}
		if (empty($data)) {
			return;
		}
		$sexes = array("male", "female", "unspecified");
		$statistics = array();
		foreach ($sexes AS $sex) {
			if (!isset($data[$sex])) {
				$statistics[System::translate($sex)] = 0;
			} else {
				$statistics[System::translate($sex)] = $data[$sex];
			}
		}
		// Build chart
		$chart = new GoogleChart(GoogleChart::TYPE_TWO_DIMENSIONAL_PIE);
		$chart->setAxes(array(GoogleChart::AXES_LEFT, GoogleChart::AXES_BOTTOM));
		$chart->setSize(120, 240);
		$chart->setLegend(array_keys($statistics));
		// Tango pallete
		$chart->setColors(array("729fcf","cc0000","d3d7cf"));
		//$chart->setLabels(GoogleChart::AXES_BOTTOM, $statistics);
		$chart->addDataSet($statistics);
		return $chart;
	}

	private static function getNumberOfInsertedItemsLastYear($tablename) {
		// Load data
		$sqls = array();
		$day = date("j");
		$moveddate = "DATE_SUB(CURDATE(), INTERVAL 3 DAY)";
		$sqls[] = "(SELECT '0' AS [id], COUNT(*) AS [number] FROM [$tablename] WHERE [inserted] >= $moveddate)";
		for ($i = 1; $i < 12; $i++) {
			$sqls[] = "(SELECT '$i' AS [id], COUNT(*) AS [number] FROM [$tablename] WHERE [inserted] <= DATE_SUB($moveddate, INTERVAL " . ($i - 1) . " MONTH) AND [inserted] >= DATE_SUB($moveddate, INTERVAL $i MONTH))";
		}
		$sql = implode(" UNION ALL ", $sqls);
		$data = dibi::query($sql)->fetchPairs("id", "number");
		// Get list of months
		$months = array();
		for ($i = 0; $i < 12; $i++) {
			$months[] = System::translate(date("M", strtotime("-$i month")));
		}
		// Build chart
		$chart = new GoogleChart(GoogleChart::TYPE_VERTICAL_BAR_GROUPED);
		$chart->setAxes(array(GoogleChart::AXES_LEFT, GoogleChart::AXES_BOTTOM));
		$chart->setSize(300, 420);
		$chart->setLabels(GoogleChart::AXES_BOTTOM, array_reverse($months));
		$chart->addDataSet(array_reverse($data));
		return $chart;
	}

}