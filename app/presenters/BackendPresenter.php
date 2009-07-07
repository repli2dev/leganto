<?php
/**
 * This class should be extended by all presenters in backend module.
 *
 * @author Jan Papousek
 */
abstract class BackendPresenter extends BasePresenter
{

	public function startup() {
		$this->setModule(Modules::getInstance()->get("base"));
	}

	public function beforeRender() {
		parent::beforeRender();
		// Basic template
		$this->template->title = Locales::get()->get("administration");

		// Menu
		foreach(Modules::getInstance()->installedModules() AS $moduleName) {
			$links = Modules::getInstance()->get($moduleName)->getSection(ModuleSection::BACKEND)->getLinks();
			foreach ($links AS $name => $descriptor) {
				$name = Locales::get($moduleName)->get($name);
				$this->addMenu($name, $descriptor["url"], $descriptor["priority"]);
			}
			$sublinks = Modules::getInstance()->get($moduleName)->getSection(ModuleSection::BACKEND)->getSublinks();
			foreach ($sublinks AS $name => $descriptor) {
				$name = Locales::get($moduleName)->get($name);
				$this->addSubMenu($name, $url);
			}
		}
	}

}