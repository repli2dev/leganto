<?php
/**
 * This class should be extended by all presenters in frontend module.
 *
 * @author Jan Papousek
 */
abstract class Frontend_BasePresenter extends BasePresenter
{
	public function beforeRender() {
		$this->template->title = NULL;

		$this->template->menu = array();
		$this->template->rss = array();

		foreach(Modules::getInstance()->installedModules() AS $moduleName) {
			$links = Modules::getInstance()->get($moduleName)->getLinks(Module::SECTION_FRONTEND);
			foreach ($links AS $name => $url) {
				$name = Locales::get($moduleName)->get($name);
				$this->template->menu[$name] = $url;
			}
			$rss = Modules::getInstance()->get($moduleName)->getLinks(Module::SECTION_RSS);
			foreach ($rss AS $name => $url) {
				$name = Locales::get($moduleName)->get($name);
				$this->template->rss[$name] = $url;
			}
		}
	}
}
?>
