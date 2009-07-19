<?php
/**
 * This class provides manipulation with roles and their permission.
 *
 * @author Jan Papousek
 */
class Users_RoleBackendPresenter extends BackendPresenter
{

	private $permission;

	private $role;

	/** @persistent */
	public $formType; // insert/edit

	public function startup() {
		$this->setModule(Modules::getInstance()->get("users"));
	}

	public function beforeRender() {
		parent::beforeRender();
		$this->addSubMenu(
			Locales::get("users")->get("roles"),
			"RoleBackend:roles"
		);
		$this->addSubMenu(
			Locales::get("users")->get("insert_role"),
			"RoleBackend:insertRole"
		);
	}

	public function renderInsertRole() {
		$this->formType = "insert";
		$this->template->form = $this->getComponent("roleForm");
	}

	public function renderInsertPermission($id_role = NULL) {
		$this->formType = "insert";
		$this->template->form = $this->getComponent("permissionForm");
	}

	public function renderEditRole($id_role) {
		$roles = new Role();
		$this->role = $roles->findAll()->where("%n = %i", Role::DATA_ID, $id_role)->fetch();

		$this->formType = "edit";
		$this->template->form = $this->getComponent("roleForm");
	}

	public function renderEditPermission($id_permission) {
		$permissions = new Permissions();
		$this->permission = $permissions->get()->where("%n = %i", Permissions::DATA_ID, $id_permission)->fetch();

		$this->formType = "edit";
		$this->template->form = $this->getComponent("permissionForm");
	}

	public function renderRoles() {
		$this->template->roles = $this->getComponent("rolesDataGrid");
	}

	public function renderDetail($id_role) {
		$roles = new Role();
		$this->role = $roles->findAll()->where("%n = %i", Role::DATA_ID, $id_role)->fetch();
		$this->template->permissions = $this->getComponent("permissionDataGrid");
		$this->template->role = $this->role;
	}

	public function insertRoleSubmitted(Form $form) {
		$values = $form->getValues();
		$roles = new Role();
		try {
			if ($roles->insert($values) == -1) {
				$form->addError(Locales::get("users")->get("role_exists"));
				return;
			}
			$this->flashMessage(Locales::get("users")->get("role_successfully_inserted"));
			$this->redirect("roles");
		}
		catch(DibiDriverException $e) {
			$form->addError(Locales::get()->get("database_error"));
			Debug::processException($e);
		}
	}

	public function editRoleSubmitted(Form $form) {
		$values = $form->getValues();
		$roles = new Role();
		try {
			$id = $values[Role::DATA_ID];
			if (!empty($values["delete"])) {
				$roles->delete($id);
				$this->flashMessage(Locales::get("users")->get("role_successfully_deleted"));
			}
			else {
				unset($values[Role::DATA_ID]);
				unset($values["delete"]);
				if (!$roles->update($id, $values)) {
					$form->addError(Locales::get("users")->get("role_exists"));
					return;
				}
				$this->flashMessage(Locales::get("users")->get("role_successfully_updated"));
			}
			$this->redirect("roles");
		}
		catch(DibiDriverException $e) {
			$form->addError(Locales::get()->get("database_error"));
			Debug::processException($e);
		}
	}

	public function insertPermissionSubmitted(Form $form) {
		$values = $form->getValues();
		$permissions = new Permissions();
		try {
			if ($permissions->insert($values) == -1) {
				$form->addError(Locales::get("users")->get("permission_exists"));
				return;
			}
			$this->flashMessage(Locales::get("users")->get("permission_successfully_inserted"));
			$this->redirect("detail", $values[Permissions::DATA_ROLE]);
		}
		catch(DibiDriverException $e) {
			$form->addError(Locales::get()->get("database_error"));
			Debug::processException($e);
		}
	}

	public function editPermissionSubmitted(Form $form) {
		$values = $form->getValues();
		$permissions = new Permissions();
		try {
			$id = $values[Permissions::DATA_ID];
			if (!empty($values["delete"])) {
				$permissions->delete($id);
				$this->flashMessage(Locales::get("users")->get("permission_successfully_deleted"));
			}
			else {
				unset($values[Permissions::DATA_ID]);
				unset($values["delete"]);
				if ($permissions->update($id, $values) == -1) {
					$form->addError(Locales::get("users")->get("permission_exists"));
					return;
				}
				$this->flashMessage(Locales::get("users")->get("permission_successfully_updated"));
			}
			$this->redirect("detail", $values[Permissions::DATA_ROLE]);
		}
		catch(DibiDriverException $e) {
			$form->addError(Locales::get()->get("database_error"));
			Debug::processException($e);
		}
	}

	/* COMPONENTS */
	protected function createRolesDataGrid($name) {
		$dataGrid= $this->getSimpleDataGrid();

		$roles = new Role();

		$dataGrid->bindDataTable($roles->findAll());
		$dataGrid->setKeyName(Role::DATA_ID);

		// columns
		$dataGrid->addColumn(Role::DATA_ID, "#");
		$dataGrid->addColumn(Role::DATA_NAME, Locales::get("users")->get("role"))
			->addFilter();

		$dataGrid->addActionColumn(Locales::get()->get("actions"));

		$dataGrid->addAction(
			Locales::get()->get("detail"),
			'detail',
			Html::el('span')->setText(Locales::get()->get("detail")), $useAjax = FALSE
		);

		$dataGrid->addAction(
			Locales::get()->get("edit"),
			'editRole',
			Html::el('span')->setText(Locales::get()->get("edit")), $useAjax = FALSE
		);

		$dataGrid->addAction(
			Locales::get("users")->get("insert_permission"),
			'insertPermission',
			Html::el('span')->setText(Locales::get("users")->get("insert_permission")), $useAjax = FALSE
		);

		return $dataGrid;
	}

	protected function createPermissionDataGrid($name) {
		$dataGrid = $this->getSimpleDataGrid();

		$permission = new Permissions();

		$dataGrid->bindDataTable(
			$permission->findAll()->where("%n = %i", Permissions::DATA_ROLE, $this->role[Role::DATA_ID])
		);
		$dataGrid->setKeyName(Permissions::DATA_ID);

		//columns
		$dataGrid->addColumn(Permissions::DATA_ID, "#");

		$moduleReplacement = array();
		foreach (Modules::getInstance()->loadedModules() AS $module) {
			$moduleReplacement[Modules::getInstance()->getId($module)] = $module;
		}
		$dataGrid->addColumn(Permissions::DATA_MODULE, Locales::get()->get("module"))
			->addSelectboxFilter($moduleReplacement);
		$dataGrid[Permissions::DATA_MODULE]->replacement =$moduleReplacement;

		$actionReplacement = array();
		$actions = array(
			Permissions::ACTION_EDIT,
			Permissions::ACTION_EDIT_ALL,
			Permissions::ACTION_INSERT,
			Permissions::ACTION_READ,
			Permissions::ACTION_READ_ALL
		);
		foreach ($actions AS $action) {
			$actionReplacement[$action] = Locales::get("users")->get("action_" . $action);
		}
		$actionReplacement[Permissions::ACTION_ALL] = Locales::get("users")->get("action_all");
		$dataGrid->addColumn(Permissions::DATA_ACTION, Locales::get()->get("action"))
			->addSelectboxFilter($actionReplacement);
		$dataGrid[Permissions::DATA_ACTION]->replacement = $actionReplacement;

		$dataGrid->addActionColumn(Locales::get()->get("actions"));

		$dataGrid->addAction(
			Locales::get()->get("edit"),
			'editPermission',
			Html::el('span')->setText(Locales::get()->get("edit")), $useAjax = FALSE
		);

		return $dataGrid;
	}

	protected function createRoleForm($name) {
		$form = new AppForm($this, $name);

		if ($this->formType == "insert") {
			$form->addGroup(Locales::get("users")->get("insert_role"));
		}
		else {
			$form->addGroup(Locales::get("users")->get("edit_role"));
		}

		$form->addText(Role::DATA_NAME, Locales::get("users")->get("role"));

		if ($this->formType == "insert") {
			$form->addSubmit("roleSubmit", Locales::get()->get("insert"));
			$form->onSubmit[] = array($this, "insertRoleSubmitted");
		}
		else {
			$form->addHidden(Role::DATA_ID);
			$form->addCheckbox("delete", Locales::get()->get("delete"));

			$form->setDefaults($this->role);

			$form->addSubmit("roleSubmit", Locales::get()->get("edit"));
			$form->onSubmit[] = array($this, "editRoleSubmitted");
		}

		return $form;
	}

	protected function createPermissionForm($name) {
		$form = new AppForm($this, $name);

		if ($this->formType == "insert") {
			$form->addGroup(Locales::get("users")->get("insert_permission"));
		}
		else {
			$form->addGroup(Locales::get("users")->get("edit_permission"));
		}

		$modules = array();
		foreach (Modules::getInstance()->installedModules() AS $id => $name) {
			$modules[$id] = Locales::get($name)->get("module_name");
		}

		$form->addSelect(
			Permissions::DATA_MODULE,
			Locales::get()->get("module"),
			$modules
		);

		$actions = array(
			Permissions::ACTION_EDIT,
			Permissions::ACTION_EDIT_ALL,
			Permissions::ACTION_INSERT,
			Permissions::ACTION_READ,
			Permissions::ACTION_READ_ALL
		);
		foreach ($actions AS $action) {
			$actionsOpt[$action] = Locales::get("users")->get("action_" . $action);
		}
		$actionsOpt[Permissions::ACTION_ALL] = Locales::get("users")->get("action_all");

		$form->addSelect(
			Permissions::DATA_ACTION,
			Locales::get()->get("action"),
			$actionsOpt
		);

		$roles = new Role();
		$form->addSelect(
			Permissions::DATA_ROLE,
			Locales::get("users")->get("role"),
			$roles->get()->fetchPairs(Role::DATA_ID, Role::DATA_NAME)
		);

		if ($this->formType == "insert") {
			$form->setDefaults(array(
				Permissions::DATA_ACTION => Permissions::ACTION_ALL
			));

			$form->addSubmit("permissionSubmit", Locales::get()->get("insert"));
			$form->onSubmit[] = array($this, "insertPermissionSubmitted");
		}
		else {
			$form->addHidden(Permissions::DATA_ID);
			$form->addCheckbox("delete", Locales::get()->get("delete"));
			$form->setDefaults($this->permission);

			$form->addSubmit("permissionSubmit", Locales::get()->get("edit"));
			$form->onSubmit[] = array($this, "editPermissionSubmitted");
		}
	}

}
