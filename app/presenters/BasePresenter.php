<?php
/**
 * This presenter should be extended by all presenters.
 *
 * @author Jan Papousek
 */
abstract class BasePresenter extends Presenter
{

	private $actions = array();

	/**
	 * Module which uses this presenter.
	 *
	 * @var Module
	 */
	private $module = NULL;

	public function beforeRender() {
		parent::beforeRender();
		// Permission checking
		if (empty($this->module)) {
			return;
		}
		$user = Environment::getUser();
		$actions = $this->getActions();
		$action = empty($actions[$this->getAction()]) ? NULL : $actions[$this->getAction()];
		if (!$user->isAllowed($this->getModule()->getName(),$action)) {
			$this->permissionDenied();
		}
		
	}

    protected function createTemplate() {
		$this->oldLayoutMode = false;

		$template = parent::createTemplate();

		// register filters
		$template->registerFilter('CurlyBracketsFilter::invoke');

		// register custom helpers
		$template->registerHelper("date", Helpers::getHelper('date'));
		$template->registerHelper("time", Helpers::getHelper('time'));

		return $template;
	}

	public function renderPermissionDenied() {
		$this->template->setFile(TEMPLATES_DIR . "/denied.phtml");
		if (!Environment::getUser()->isAuthenticated()) {
			$this->template->userComponent = $this->getComponent("userComponent");
		}
		$this->flashMessage(Locales::get("users")->get("permission_denied"),"error");
	}

	/**
	 * It adds a RSS channel to page header.
	 *
	 * @param string $url The url of the RSS channel
	 * @throws NullPointerException if the $url is empty.
	 */
	public function addRss($url) {
		if (empty($url)) {
			throw new NullPointerException("url");
		}
		$this->template->stylesheets[] = $url;
	}

	/**
	 * It adds a stylesheet to page header/
	 *
	 * @param string $filename The path to the stylesheet file
	 * @param string $media The media the css is used for.
	 * @throws NullPointerException if the $filename or $media is empty.
	 */
	public function addStylesheet($filename, $media = "screen,projection,tv") {
		if (empty($filename)) {
			throw new NullPointerException("filename");
		}
		if (empty($media)) {
			throw new NullPointerException("media");
		}
		if (preg_match("~^(\w+)://~u", $filename)) {
			// absolute path
			$url = $filename;
		}
		else {
			// relative path
			$url = $this->getTemplate()->baseUri . 'css' . $filename;
		}
		if (!isset($this->template->stylesheets)) {
			$this->template->stylesheets = array();
		}
		$this->template->stylesheets[$url] = $media;
	}


	/**
	 * Creates a component - calls an method $this->create{Name}
	 * @param string $name the name of the compoenent to create
	 */
	protected function createComponent($name) {
		if (String::lower($name) === "component") {
			throw new InvalidArgumentException("Argument name '$name' is not allowed.");
		}

		$callback = array($this, "create" . ucfirst($name));

		if (method_exists($callback[0], $callback[1])) {
			$component = call_user_func($callback, $name);

			if ($component instanceof IComponent && $component->getParent() === NULL) {
				$this->addComponent($component, $name);
			}

		}
		else {
			parent::createComponent($name);
		}
	}

	/**
	 * It sets a module which uses this presenter.
	 *
	 * @param Module $module
	 * @throws NullPointerException if the $module is empty
	 */
	public function setModule(Module $module) {
		if (empty($module)) {
			throw new NullPointerException("module");
		}
		$this->module = $module;
	}

	/**
	 * It returns a module which uses this presenter.
	 *
	 * @return Module
	 * @throws NullPointerException if the presenter has no module whis uses it.
	 */
	public function getModule() {
		if (empty($this->module)) {
			throw new NullPointerException("module");
		}
		return $this->module;
	}

	public function permissionDenied() {
		$this->redirect("permissionDenied");
	}

	/**
	 * It sets action types (this is important to check permission).
	 *
	 * @param array|string $actions Array in format 'action name' => 'type'
	 */
	public function setActions(array $actions) {
		$this->actions = $actions;
	}

	/**
	 * It returns action types in the asscociative array.
	 *
	 * @return array|string Array in format 'action name' => 'type'.
	 */
	public function getActions() {
		return $this->actions;
	}

	/**
	 * It returns simple instance of DataGrid
	 *
	 * @return DataGrid
	 */
	protected function getSimpleDataGrid() {
		$dataGrid = new DataGrid();

		// general options
		$dataGrid->itemsPerPage = 25;
		$dataGrid->multiOrder = false;

		// link additional styles
		$this->addStylesheet('/datagrid.css');

		return $dataGrid;
	}

	/* COMPONENTS */
	protected function createUserComponent($name) {
		return new UserComponent();
	}
}
