<?php

/**
 * Caller for google chart api
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\Templating;

use Nette\InvalidStateException,
    Nette\Utils\Strings;

class GoogleChart {
	const AXES_BOTTOM = 'x';

	const AXES_LEFT = 'y';

	const AXES_RIGHT = 'r';

	const AXES_TOP = 't';

	const TYPE_VERTICAL_BAR_GROUPED = 'bvg';

	const TYPE_VERTICAL_BAR_STACKED = 'bvs';

	const TYPE_TWO_DIMENSIONAL_PIE = 'p';

	private $axes;
	private $colors;
	private $height;
	private $labels = array();
	private $legend;
	private $maxvalue;
	private $minvalue;
	private $name;
	private $type;
	private $width;

	/**
	 * Create instance of GoogleChart
	 * @param string $type Type of Chart (@see self::TYPE*)
	 * @param mixed $data Data to visualize
	 */
	public function __construct($type, $data = array()) {
		$this->type = $type;
	}

	/**
	 * Add data set into chart
	 * @param array $data DataSet
	 */
	public function addDataSet(array $data) {
		$this->data[] = $data;
	}

	/**
	 * Return prepared URL for Google Chart (Google API)
	 * @return string URL of chart
	 * @throws InvalidStateException if there are no data, no selected type or no specified size
	 */
	public function getLink() {
		$link = "http://chart.apis.google.com/chart?";
		if (empty($this->data)) {
			throw new InvalidStateException("There is no data to show.");
		}
		if (empty($this->type)) {
			throw new InvalidStateException("The type of graph has to be specified.");
		}
		if (empty($this->height) || empty($this->width)) {
			throw new InvalidStateException("The size of graph has to be specified.");
		}
		$this->autoScale();
		// Set data
		$first = true;
		foreach ($this->data AS $dataset) {
			if ($first) {
				$link .="chd=t:";
				$first = false;
			} else {
				$link .= "|";
			}
			$link .= implode(",", $dataset);
		}
		// Set type
		$link .= "&cht=" . $this->type;
		// Set size
		$link .= "&chs=" . $this->width . "x" . $this->height;
		// Set transparent background
		$link .= "&chf=bg,s,65432100";
		// Set axes
		if (!empty($this->axes)) {
			$link .= "&chxt=" . implode(",", $this->axes);
		}
		// Set labels
		$this->autoLabels();
		if (!empty($this->labels)) {
			$link .= "&chxl=";
			foreach ($this->getSortedLabels() AS $key => $labels) {
				if (!Strings::endsWith($link, "|") && !Strings::endsWith($link, "=")) {
					$link .= "|";
				}
				$link .= $key . ":|" . implode("|", $labels);
			}
		}
		// Set colors
		if (!empty($this->colors)) {
			$link .= "&chco=" . implode("|", $this->colors);
		}
		// Set legend
		if (!empty($this->legend)) {
			$link .= "&chdl=" . implode("|", $this->legend);
		}
		// Set name
		if (!empty($this->name)) {
			$link .= "&chtt=" . $this->name;
		}
		// Set max value
		if (isset($this->maxvalue) && isset($this->minvalue)) {
			$link .= "&chds=" . $this->minvalue . "," . $this->maxvalue;
		}
		return $link;
	}

	/**
	 * Set axes
	 * @param array $axes 
	 */
	public function setAxes(array $axes) {
		$this->axes = $axes;
	}

	/**
	 * Set labels of axes
	 * @param string $axes
	 * @param array $labels
	 */
	public function setLabels($axes, array $labels) {
		$this->labels[$axes] = $labels;
	}

	/**
	 * Set colors
	 * @param array $colors List of colors
	 */
	public function setColors(array $colors) {
		$this->colors = $colors;
	}

	/**
	 * Set legend of chart
	 * @param array $legend
	 */
	public function setLegend(array $legend) {
		$this->legend = $legend;
	}

	/**
	 * Set name
	 * @param string $name 
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Set size (dimension)
	 * @param int $height
	 * @param int $width 
	 */
	public function setSize($height, $width) {
		$this->height = $height;
		$this->width = $width;
	}

	// PRIVATE METHODS

	private function autoLabels() {
		if (empty($this->labels[self::AXES_LEFT])) {
			$step = $this->maxvalue / 5;
			// Check if step would be float (does not make sense)
			if (is_float($step)) {
				$this->setLabels(self::AXES_LEFT, array(0, $this->maxvalue));
			} else {
				$this->setLabels(self::AXES_LEFT, array(0 * $step, 1 * $step, 2 * $step, 3 * $step, 4 * $step, 5 * $step));
			}
		}
	}

	private function autoScale() {
		$maxvalue = 0;
		foreach ($this->data AS $dataset) {
			if (!empty($dataset)) {
				$max = max($dataset);
				if ($max >= $maxvalue) {
					$maxvalue = $max;
				}
			}
		}
		$roundedMax = 1;
		while ($roundedMax < $maxvalue)
			$roundedMax *= 10;
		if (($roundedMax / 2) > $maxvalue) {
			$roundedMax = $roundedMax / 2;
		}
		$this->setScale(0, $roundedMax);
	}

	private function getSortedLabels() {
		$result = array();
		foreach ($this->axes AS $axes) {
			if (!empty($this->labels[$axes])) {
				$result[] = $this->labels[$axes];
			} else {
				$result[] = array();
			}
		}
		return $result;
	}

	private function setScale($minvalue, $maxvalue) {
		$this->minvalue = $minvalue;
		$this->maxvalue = $maxvalue;
	}

}
