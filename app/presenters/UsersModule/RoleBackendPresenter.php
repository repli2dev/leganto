<?php
/**
 * This class provides manipulation with roles and their permission.
 *
 * @author Jan Papousek
 */
class Users_RoleBackendPresenter extends BackendPresenter
{

	private $role;

	public function startup() {
		$this->setModule(Modules::getInstance()->get("users"));
	}

	public function insertRole() {
		
	}

	public function insertPermission($id_role = NULL) {
		
	}

	public function editRole($id_role) {

	}

	public function editPermission($id_permission) {

	}

	public function renderRoles() {
		$this->template->roles = $this->getComponent("rolesDataGrid");
	}

	public function renderDetail($id_role) {
		$roles = new Role();
		$this->role = $roles->get()->where("%n = %i", Role::DATA_ID, $id_role)->fetch();
		$this->template->permissions = $this->getComponent("permissionDataGrid");
		$this->template->role = $this->role;
	}

	/* COMPONENTS */
	protected function createRolesDataGrid($name) {
		$dataGrid= $this->getSimpleDataGrid();

		$roles = new Role();

		$dataGrid->bindDataTable($roles->get());
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
			Locales::get("users")->get("insert_permission"),
			'insertPermission',
			Html::el('span')->setText(Locales::get()->get("insert")), $useAjax = FALSE
		);

		return $dataGrid;
	}

	protected function createPermissionDataGrid($name) {
		$dataGrid = $this->getSimpleDataGrid();

		$permission = new Permissions();

		$dataGrid->bindDataTable(
			$permission->get()->where("%n = %i", Permissions::DATA_ROLE, $this->role[Role::DATA_ID])
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

		return $dataGrid;
	}

}
