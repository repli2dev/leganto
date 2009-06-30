<?php

//TODO:
//		* Create stylesheets of the modules
//		* Create actions of the module - they are important to authorization

/**
 * This class represents all avaiable modules.
 *
 * @author Jan Papousek
 */
class Modules
{

	/**
	* The singleton instance
	*
	* @var Site
	*/
	private static $singleton;

	/**
	 * Installed modules
	 *
	 * @var array|string The module names
	 */
	private $installedModules;

	/**
	 * Modules which are avaiable in config directory.
	 *
	 * @var array|Module
	 */
	private $loadedModules;

	/**
	 * Constructor. The class loads all avaiable modules.
	 *
	 * @throws AmbiguousServiceException if there are more modules with the same name.
	 * @throws DependenceException if there is a dependence error.
	 */
	public function  __construct() {
		// Module loading
		$this->loadedModules = array();
		foreach (glob(MODULES_DIR ."/*.ini") AS $filename) {
			$module = new Module($filename);
			if (!empty ($this->loadedModules[$module->getName()])) {
				throw new AmbiguousServiceException($filename);
			}
			$this->loadedModules[$module->getName()] = $module;
		}
		if (!empty(Environment::getConfig('modules')->auto)) {
			// Installation of modules.
			foreach ($this->loadedModules AS $module) {
				if ($module->isEnabled()) {
					$this->install($module->getName());
				}
			}
			// Unistallation of modules
			foreach ($this->installedModules AS $module) {
				$this->uninstall($module);
			}
			$this->installedModules = array();
		}
		// Dependence checking
		// FIXME: It should be more effective.
		foreach ($this->loadedModules() AS $module) {
			if ($this->isAvaiable($module) && $this->get($module)->isEnabled()) {
				foreach ($this->get($module)->getDependence() AS $dependence) {
					if (!$this->isAvaiable($dependence) || !$this->get($dependence)->isEnabled()) {
						throw new DependenceException($module . " -> " . $dependence);
					}
				}
			}
		}

	}

	/**
	 * It removes all MySQL tables which are not installed.
	 *
	 * @throws DibiDriverExcpetion if there is a problem to work with database.
	 */
	private function clearDatabase() {
		$moduleTable = new ModuleTable();
		$installedTables = $moduleTable->get();
		$dbTables = dibi::getDatabaseInfo()->getTableNames();
		foreach($dbTables AS $table) {
			if (!in_array($table, $installedTables)) {
				dibi::query("DROP TABLE %n", $table);
			}
		}
	}

	/**
	 * It returns a module.
	 *
	 * @param string $name Module name.
	 * @return Module
	 * @throws NullPointerException if the $name is null.
	 * @throws DataNotFoundException if the module is not avaiable.
	 */
	public function get($name) {
		if (empty($name)) {
			throw new NullPointerException("name");
		}
		if (!$this->isAvaiable($name)) {
			throw new DataNotFoundException($name);
		}
		return $this->loadedModules[$name];
	}

	/**
	 * It returns module ID
	 * 
	 * @param string $module Module name
	 * @throws NullPointerException if the $module is empty.
	 * @throws DataNotFoundException if the module does not exist.
	 */
	public function getId($module) {
		if (empty($module)) {
			throw new NullPointerException("module");
		}
		// FIXME: More effective
		foreach ($this->installedModules() AS $id => $name) {
			if ($name == $module) {
				return $id;
			}
		}
		throw new DataNotFoundException("module: $module");
	}

	/**
	 * It returns the instance of this singleton class.
	 *
	 * @return Modules
	 */
	final public static function getInstance() {
		if (empty(self::$singleton)) {
			self::$singleton = new Modules();
		}
		return self::$singleton;
	}

	/**
	 * It returns a name of MySQL table which the model work with.
	 *
	 * @return string
	 */
	public static function getTable() {
		$tables = Environment::getConfig('tables');
		return (!empty($tables->module) ? $tables->module : 'module');
	}

	/**
	 * It checks is the specified module exists.
	 *
	 * @param string $module Module name.
	 * @return boolean
	 * @throws NullPointerException if the $module is empty.
	 */
	public function isAvaiable($module) {
		if (empty($module)) {
			throw new NullPointerException("module");
		}
		if (in_array($module, $this->installedModules()) && !empty($this->loadedModules[$module])) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}

	/**
	 * It installs an uninstalled module
	 *
	 * @param string $module Module name
	 * @return boolean It returns TRUE if the module was installed,
	 *		otherwise FALSE
	 * @throws NullPointerException if the $module is empty.
	 * @throws DataNotFoundException if the module is not loaded.
	 * @throws DibiDriverExcpetion if there is a problem to work with database.
	 */
	public function install($module) {
		if ($this->isAvaiable($module)) {
			return FALSE;
		}
		if (empty($this->loadedModules[$module])) {
			throw new DataNotFoundException("module");
		}
		// Module register
		dibi::insert(self::getTable(), array("name" => $module))->execute();
		$this->installedModules = NULL;
		$moduleId = dibi::insertId();
		$tables = new ModuleTable();
		// Tables installation
		$dir = $this->get($module)->getDirectoryWithDatabase();
		if (!empty ($dir)) {
			foreach (glob($dir . "/*.sql") AS $file) {
				dibi::loadFile($file);
			}
		}
		$this->synchronizeDatabase($module);
		return TRUE;
	}

	/**
	 * It returns installed modules loaded from database.
	 *
	 * @return array|string
	 * @throws DibiDriverExcpetion if there is a problem to work with database.
	 */
	public function installedModules() {
		if (empty($this->installedModules)) {
			$this->installedModules = array();
			$rows = dibi::query(
				"SELECT [id_module], [name] FROM %n", self::getTable()
			);
			$this->installedModules = $rows->fetchPairs("id_module", "name");
		}
		return $this->installedModules;
	}

	/**
	 * It installes all unistalled MySQL tables to specified module.
	 *
	 * @param string $module Module name
	 * @throws NullPointerException if the $module is empty.
	 * @throws IOException if there is no definition of id columns.
	 * @throws DataNotFoundException if the module does not exist
	 */
	private function synchronizeDatabase($module) {
		$loadedTables = $this->get($module)->getTables();
		$id = dibi::dataSource("SELECT * FROM %n", self::getTable())
			->where("[name] = %s", $module)
			->fetch()->id_module;
		$moduleTable = new ModuleTable();
		$installedTables = array_keys($moduleTable->get()->fetchAssoc(ModuleTable::DATA_TABLE));
		$dbTables = dibi::getDatabaseInfo()->getTables();
		foreach($dbTables AS $table) {
			// If the table is already installed, skyp this cycle.
			if (in_array($table->getName(), $installedTables)) {
				continue;
			}
			// Installation
			$input = array();
			// ID column name
			if (!empty($loadedTables[$table->getName()]) && !empty($loadedTables[$table->getName()]["id"])) {
				$input[ModuleTable::DATA_ID_COLUMN] = $loadedTables[$table->getName()]["id"];
			}
			else {
				foreach ($table->getColumnNames() AS $column) {
					if (substr_count($column, "id_") > 0) {
						$input[ModuleTable::DATA_ID_COLUMN] = $column;
						break;
					}
				}
				if (empty($input[ModuleTable::DATA_ID_COLUMN])) {
					throw new IOException("id_column_name");
				}
			}
			// Name of column which contains an entity name
			if (!empty($loadedTables[$table->getName()]) && !empty($loadedTables[$table->getName()]["name"])) {
				$input[ModuleTable::DATA_NAME_COLUMN] = $loadedTables[$table->getName()]["name"];
			}
			$input[ModuleTable::DATA_MODULE] = $id;
			$input[ModuleTable::DATA_TABLE] = $table->getName();
			$moduleTable->insert($input);
		}
	}

	/**
	 * It uninstall an uloaded module
	 *
	 * @param string $module Module name.
	 * @return boolean It returns FALSE if the module was not installed
	 *		(maybe because it is loaded), TRUE if the module
	 *		was succesfully unistalled.
	 * @throws NullPointerException if the $module is empty.
	 * @throws DataNotFoundException if the module is not installed
	 * @throws DibiDriverExcpetion if there is a problem to work with database.
	 */
	public function uninstall($module) {
		if (empty($module)) {
			throw new NullPointerException("module");
		}
		if (!in_array($module, $this->installedModules())) {
			throw new DataNotFoundException("module");
		}
		if (in_array($module, $this->loadedModules())) {
			return FALSE;
		}
		$tables = new ModuleTable();
		$moduleRow = dibi::dataSource("SELECT * FROM %n", self::getTable())
			->where("[name] = %s", $module)
			->fetch();
		$rows = $tables->get()
			->where("%n = %i", ModuleTable::DATA_MODULE, $moduleRow["id_module"]);
		while ($row = $rows->fetch()) {
			dibi::query("DROP TABLE IF EXISTS %n", $row[ModuleTable::DATA_TABLE]);
		}
		$tables->deleteByModule($moduleRow["id_module"]);
		dibi::delete(self::getTable())
			->where("[name] = %s",$module)
			->execute();
	}

	/**
	 * It returns loaded modules.
	 *
	 * @return array|string Module names.
	 */
	public function loadedModules() {
		return (array_keys($this->loadedModules));
	}

	public function __clone() {}

	public function __wakeup() {}

}
