<?php
/**
 * This class represents a module.
 *
 * @author Jan Papousek
 */
class Module
{

	/**
	 * Backend section - this section is avaiable for admins.
	 *
	 * @var string
	 */
	const SECTION_BACKEND = "backend";

	/**
	 * Frontend section - this section is avaiable for common users.
	 *
	 * @var string
	 */
	const SECTION_FRONTEND = "frontend";

	/**
	 * RSS section - the RSS channels which should be avaible on whole site.
	 *
	 * @var string
	 */
	const SECTION_RSS = "rss";

	/**
	 * Names of avaiable components in specified sections.
	 *
	 * @var array|(array|string)
	 */
	private $componets = array();

	/**
	 * Components directory
	 *
	 * @var string
	 */
	private $componentsDir;

	/**
	 * Database directory
	 *
	 * @var string
	 */
	private $databaseDir;

	/**
	 * Dependence on other modules
	 *
	 * @var array|string The module names.
	 */
	private $dependence;

	/**
	 * Module availability
	 *
	 * @var boolean
	 */
	private $enabled;

	/**
	 * Avaiable links in specified sections
	 *
	 * @var array|(array|string)
	 */
	private $links = array();

	/**
	 * Locales directory
	 *
	 * @var string
	 */
	private $localesDir;

	/**
	 * Models directory
	 *
	 * @var string
	 */
	private $modelsDir;

	/**
	 * Presenters directory
	 *
	 * @var string
	 */
	private $presentersDir;

	/**
	 * Module name
	 *
	 * @var string
	 */
	private $name;

	/**
	 * Avaiable sublinks in specified sections
	 *
	 * @var array|(array|string)
	 */
	private $sublinks = array();

	/**
	 * Used MySQL tables.
	 *
	 * @var array|(array|mixed)
	 */
	private $tables;

	/**
	 * It creates a new module instance.
	 * 
	 * @param string $fileName The file with module configuration.
	 * @throws NullPointerException if $fileName is empty
	 * @throws IOException if the file does not exists.
	 */
	public function  __construct($fileName) {
		if (empty($fileName)) {
			throw new NullPointerException("fileName");
		}
		if (!file_exists($fileName)) {
			throw new IOException($fileName);
		}
		$fileName = Environment::expand($fileName);
		$config = Config::fromFile($fileName);

		// Module name
		$this->name = $config["description"]->name;

		// Is enabled?
		if (isset($config["description"]->enabled)) {
			$this->enabled = !empty($config["description"]->enabled);
		}
		else {
			$this->enabled = TRUE;
		}
		
		// Used directories
		$this->localesDir = Environment::expand($config["directories"]->locales);
		$this->modelsDir = Environment::expand($config["directories"]->models);
		$this->presentersDir = Environment::expand($config["directories"]->presenters);
		$this->componentsDir = Environment::expand($config["directories"]->components);
		if (isset($config["directories"]->database)) {
			$this->databaseDir = Environment::expand($config["directories"]->database);
		}
		else {
			$this->databaseDir = NULL;
		}

		// Dependence on other modules
		if (isset($config["description"]->dependence)) {
			$this->dependence = $config["description"]->dependence;
		}
		else {
			$this->dependence = array();
		}

		// Used MySQL tables
		$this->tables = array();
		if (isset ($config->tables)) {
			foreach ($config->tables AS $name => $table) {
				$this->tables[$name] = array();
				foreach ($table AS $key => $value) {
					$this->tables[$name][$key] = $value;
				}
			}
		}

		foreach ($config AS $section => $content) {
			if ($section === "description" || $section === "directories" || $section === "tables") {
				continue;
			}
			// Links
			$this->links[$section] = array();
			if (isset ($content->links)) {
				foreach ($content->links AS $name => $url) {
					$this->links[$section][$name] = $url;
				}
			}
			// Sublinks
			$this->sublinks[$section] = array();
			if (isset ($content->sublinks)) {
				foreach ($content->sublinks AS $name => $url) {
					$this->sublinks[$section][$name] = $url;
				}
			}
			// Components
			$this->componets[$section] = array();
			if (isset ($content->components)) {
				foreach ($content->components AS $component) {
					$this->components[$section][] = $component;
				}
			}
		}
	}

	/**
	 * It returns names of components which are avaiable for the specified section.
	 *
	 * @param string $section The section name.
	 * @return array|string
	 * @throws NullPointerException if the $section is empty.
	 * @throws DataNotFoundException if the section does not exist.
	 */
	public function getComponents($section) {
		if (empty($section)) {
			throw new NullPointerException("section");
		}
		if (empty($this->componentsDir[$section])) {
			throw new DataNotFoundException("section");
		}
		return $this->componentsDir[$section];
	}

	/**
	 * It returns names of modules which this module is dependent on.
	 *
	 * @return array|string
	 */
	public function getDependence() {
		return $this->dependence;
	}

	/**
	 * It returns a directory with database installation.
	 *
	 * @return string
	 */
	public function getDirectoryWithDatabase() {
		return $this->databaseDir;
	}

	/**
	 * It returns a directory with components.
	 *
	 * @return string
	 */
	public function getDirectoryWithComponents() {
		return $this->componentsDir;
	}

	/**
	 * It returns a directory with locales.
	 *
	 * @return string
	 */
	public function getDirectoryWithLocales() {
		return $this->localesDir;
	}

	/**
	 * It returns a directory with models.
	 *
	 * @return string
	 */
	public function getDirectoryWithModels() {
		return $this->modelsDir;
	}

	/**
	 * It returs a directory with presenters.
	 *
	 * @return string
	 */
	public function getDirectoryWithPresenters() {
		return $this->presentersDir;
	}

	/**
	 * It returns avaiable links for the specified section.
	 *
	 * @param string $section The section name.
	 * @return array|string The keys mean names and values mean URLs of links.
	 * @throws NullPointerException if the $section is empty.
	 * @throws DataNotFoundException if the section does not exist.
	 */
	public function getLinks($section) {
		if (empty($section)) {
			throw new NullPointerException("section");
		}
		if (empty($this->links[$section])) {
			throw new DataNotFoundException("section");
		}
		return $this->links[$section];
	}

	/**
	 * It returns a module name.
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * It returns avaiable sublinks for the specified section.
	 *
	 * @param string $section The section name.
	 * @return array|string The keys mean names and values mean URLs of sublinks.
	 * @throws NullPointerException if the $section is empty.
	 * @throws DataNotFoundException if the section does not exist.
	 */
	public function getSublinks($section) {
		if (empty($section)) {
			throw new NullPointerException("section");
		}
		if (empty($this->sublinks[$section])) {
			throw new DataNotFoundException("section");
		}
		return $this->sublinks[$section];
	}

	/**
	 * It returns used MySQL tables.
	 *
	 * @return array|string MySQL table names.
	 */
	public function getTables() {
		return $this->tables;
	}

	/**
	 * It checks if the module is enabled.
	 *
	 * @return boolean
	 */
	public function isEnabled() {
		return $this->enabled;
	}
}