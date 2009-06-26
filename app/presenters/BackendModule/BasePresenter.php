<?php
/**
 * This class should be extended by all presenters in backend module.
 *
 * @author Jan Papousek
 */
abstract class Backend_BasePresenter extends BasePresenter
{

	public function beforeRender() {
		$this->template->title = Locales::get()->get("administration");

		$this->template->menu = array();

		foreach(Modules::getInstance()->installedModules() AS $moduleName) {
			$links = Modules::getInstance()->get($moduleName)->getLinks(Module::SECTION_BACKEND);
			foreach ($links AS $name => $url) {
				$name = Locales::get($moduleName)->get($name);
				$this->template->menu[$name] = $url;
			}		
		}
	}

}
?>