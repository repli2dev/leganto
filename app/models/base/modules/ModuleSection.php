<?php
/*
 * The web basis called Eskymo
 *
 * @copyright   Copyright (c) 2004, 2009 Jan Papousek, Jan Drabek
 * @link        [--- ESKYMO REPOSITORY LINK ---]
 * @category    Eskymo
 * @package     Eskymo\Modules
 * @version     2009-07-04
 */

/*namespace Eskymo\Modules;*/

/**
 * This class provides reading of data from sections in config file.
 *
 * @author Jan Papousek
 */
class ModuleSection
{

	/**
	 * Backend section - this section is avaiable for admins.
	 *
	 * @var string
	 */
	const BACKEND = "backend";

	/**
	 * Frontend section - this section is avaiable for common users.
	 *
	 * @var string
	 */
	const FRONTEND = "frontend";

	/**
	 * RSS section - the RSS channels which should be avaible on whole site.
	 *
	 * @var string
	 */
	const RSS = "rss";

	/**
	 * Components
	 *
	 * @var array|int Array in format 'component name' => 'priority'.
	 */
	 private $components;

	/**
	 * Links
	 *
	 * @var array|(array|mixed) 'link name' => ('url' => ..., 'priority' => ...).
	 */
	private $links;

	/**
	 * Section name.
	 * 
	 * @var string
	 */
	private $name;

	/**
	 * RSS channels.
	 *
	 * @var array|string
	 */
	private $rss;

	/**
	 * Subinks
	 *
	 * @var array|(array|mixed) 'sublink name' => ('url' => ..., 'priority' => ...).
	 */
	private $sublinks;

	/**
	 * It creates a new section descriptor.
	 *
	 * @param string Section name.
	 * @param Config A section from config file.
	 * @throws NullPointerException if the $name is empty.
	 */
	public function __construct($name, Config $section) {
		if (empty($name)) {
			throw new NullPointerException("name");
		}
		// Links
		$this->links = array();
		if (isset ($section->links)) {
			foreach ($section->links AS $name => $descriptor) {
				$this->links[$name] = array(
					"url" => $descriptor["url"],
					"priority" => $descriptor["priority"]
				);
			}
		}
		// Sublinks
		$this->sublinks = array();
		if (isset ($section->sublinks)) {
			foreach ($section->sublinks AS $name => $descriptor) {
				$this->sublinks[$name] = array(
					"url" => $descriptor["url"],
					"priority" => $descriptor["priority"]
				);
			}
		}
		// Components
		$this->components = array();
		if (isset ($section->components)) {
			foreach ($section->components AS $name => $priority) {
				$this->components[$name] = $priority;
			}
		}
		// RSS
		$this->rss = array();
		if (isset($section->rss)) {
			foreach ($section->rss AS $rss) {
				$this->rss[] = $rss;
			}
		}
	}

	/**
	 * It returns components class names, which are located in section,
	 * and their priority,
	 *
	 * @return array|int The result in format: 'component name' => 'priority'
	 */
	public function getComponents() {
		return $this->components;
	}

	/**
	 * It returns section links.
	 *
	 * @return array|(array|mixed) Result in format:
	 *		'link name' => ('url' => ..., 'priority' => ...).
	 */
	public function getLinks() {
		return $this->links;
	}

	/**
	 * It returns section name.
	 *
	 * @return string
	 */
	public function getName() {
		return $name;
	}

	/**
	 * It returns RSS channels.
	 *
	 * @return array|string
	 */
	public function getRSS() {
		return $this->rss;
	}

	/**
	 * It returns section sublinks.
	 *
	 * @return array|(array|mixed) Result in format:
	 *		'sublink name' => ('url' => ..., 'priority' => ...).
	 */
	public function getSublinks() {
		return $this->sublinks;
	}

}

