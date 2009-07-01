<?php
/**
 * The administration of the users.
 *
 * @author Jan Papousek
 */
class Users_BackendPresenter extends BackendPresenter
{

	public function startup() {
		parent::startup();
		$this->setModule(Modules::getInstance()->get("users"));
	}

	public function renderDefault() {
		$this->template->users = $this->getComponent("usersDataGrid");
	}

	public function renderEdit($id_user) {
		
	}

	/* COMPONENTS */
	protected function createUsersDataGrid($name) {
		$dataGrid = $this->getSimpleDataGrid();

		$users = new Users();

		$dataGrid->bindDataTable($users->get());
		$dataGrid->keyName = Users::DATA_ID;

		// setup columns
		$dataGrid->addColumn(Users::DATA_ID, "#");
		$dataGrid->addColumn(Users::DATA_NICKNAME, Locales::get("users")->get("nickname"))
			->addFilter();

		$dataGrid->addActionColumn(Locales::get()->get("actions"));
		$dataGrid->addAction(Locales::get()->get("edit"), 'edit', Html::el('span')->setText(Locales::get()->get("edit")), $useAjax = FALSE);

		return $dataGrid;
	}

}
