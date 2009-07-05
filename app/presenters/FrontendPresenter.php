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
			$links = Modules::getInstance()->get($moduleName)->getLinks(Module::SECTION_FRONTEND);
			foreach ($links AS $name => $url) {
				$name = Locales::get($moduleName)->get($name);
				$this->addMenu($name, $url);
			}
			$sublinks = Modules::getInstance()->get($moduleName)->getSublinks(Module::SECTION_FRONTEND);
			foreach ($sublinks AS $name => $url) {
				$name = Locales::get($moduleName)->get($name);
				$this->addSubMenu($name, $url);
			}
			$rss = Modules::getInstance()->get($moduleName)->getLinks(Module::SECTION_RSS);
			foreach ($rss AS $name => $url) {
				$name = Locales::get($moduleName)->get($name);
				$this->addRss($name, $url);
			}
		}

		$this->template->userComponent = $this->getComponent("userComponent");
	}
}