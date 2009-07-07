<?php
/**
 * This class should be extended by all presenters in frontend module.
 *
 * @author Jan Papousek
 */
abstract class FrontendPresenter extends BasePresenter
{
	public function beforeRender() {
		parent::beforeRender();
		$this->template->title = Locales::get()->get("module_name");

		foreach(Modules::getInstance()->installedModules() AS $moduleName) {
			$links = Modules::getInstance()->get($moduleName)->getSection(ModuleSection::FRONTEND)->getLinks();
			foreach ($links AS $name => $descriptor) {
				$name = Locales::get($moduleName)->get($name);
				$this->addMenu($name, $descriptor["url"], $descriptor["priority"]);
			}
			$sublinks = Modules::getInstance()->get($moduleName)->getSection(ModuleSection::FRONTEND)->getSublinks();
			foreach ($sublinks AS $name => $descriptor) {
				$name = Locales::get($moduleName)->get($name);
				$this->addSubMenu($name, $url);
			}
			$rss = Modules::getInstance()->get($moduleName)->getSection(ModuleSection::FRONTEND)->getRSS();
			foreach ($rss AS $name => $url) {
				$name = Locales::get($moduleName)->get($name);
				$this->addSubMenu($name, $descriptor["url"], $descriptor["priority"]);
			}
			$components = Modules::getInstance()->get($moduleName)->getSection(ModuleSection::FRONTEND)->getComponents();
			foreach ($components AS $name => $priority) {
				$this->addModuleComponent($name, $priority);
			}
		}
	}
}