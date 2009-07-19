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
        $this->template->currentUser = Locales::get('users')->get("current_user");
        $this->template->back= Locales::get()->get("back");
        $this->template->settings= Locales::get()->get("settings");
        $this->template->modules= Locales::get()->get("modules");
        $this->template->logout= Locales::get('users')->get("logout");
        $domain = Site::getInstance()->getDomain();
        $this->template->domain = $domain[Domain::DATA_URI];
        $this->template->date = date("Y-m-d H:i:s");
        $this->template->user = Environment::getUser()->getIdentity();
        $this->template->link_logout  = NULL;

		// Menu
		foreach($modules  AS $moduleName) {
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
			$components = Modules::getInstance()->get($moduleName)->getSection(ModuleSection::BACKEND)->getComponents();
			foreach ($components AS $name => $priority) {
				$this->addModuleComponent($name, $priority);
			}
		}
	}

}