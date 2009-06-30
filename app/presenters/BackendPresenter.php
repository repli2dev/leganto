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

		$this->template->rss = array();
		$this->template->menu = array();

		// Menu
		foreach(Modules::getInstance()->installedModules() AS $moduleName) {
			$links = Modules::getInstance()->get($moduleName)->getLinks(Module::SECTION_BACKEND);
			foreach ($links AS $name => $url) {
				$name = Locales::get($moduleName)->get($name);
				$this->template->menu[$name] = $url;
			}
		}
	}

}