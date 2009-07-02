<?php
/**
 * The administration of the users.
 *
 * @author Jan Papousek
 */
class Users_BackendPresenter extends BackendPresenter
{

	/**
	 * The user which is currently updated
	 * 
	 * @var DibiRow
	 */
	private $updatedUser;

	public function startup() {
		parent::startup();
		$this->setModule(Modules::getInstance()->get("users"));
	}

	public function renderDefault() {
		$this->template->users = $this->getComponent("usersDataGrid");
	}

	/** @Secured(action=edit_all) */
	public function renderEdit($id_user) {
		$users = new Users();
		$this->updatedUser = $users->get()
			->where("%n = %i", Users::DATA_ID, $id_user)->fetch();
		$this->template->form = $this->getComponent("userInfoForm");
		$this->template->user = $this->updatedUser;
	}

	/** @Secured(action=edit_all) */
	public function editUserSubmitted(Form $form) {
		$values = $form->getValues();
		$users = new Users();
		$id = $values[Users::DATA_ID];
		unset($values[Users::DATA_ID]);
		try {
			$users->update($id, $values);
			$this->flashMessage(Locales::get("users")->get("user_successfully_updated"), "success");
			$this->redirect("default");
		}
		catch(DibiDriverException $e) {
			$form->addError(Locales::get()->get("database_error"));
			Debug::processException($e);
		}
	}

	/* COMPONENTS */
	protected function createUsersDataGrid($name) {
		$dataGrid = $this->getSimpleDataGrid();

		$users = new Users();
		$roles = new Role();
		$lng = new Language();

		$dataGrid->bindDataTable($users->get());
		$dataGrid->keyName = Users::DATA_ID;

		// setup columns
		$dataGrid->addColumn(Users::DATA_ID, "#");

		$dataGrid->addColumn(Users::DATA_NICKNAME, Locales::get("users")->get("nickname"))
			->addFilter();

		$rolesReplacement = $roles->get()->fetchPairs(Role::DATA_ID, Role::DATA_NAME);
		$dataGrid->addColumn(Users::DATA_ROLE, Locales::get("users")->get("role"))
			->addSelectboxFilter($rolesReplacement);
		$dataGrid[Users::DATA_ROLE]->replacement = $rolesReplacement;

		$lngReplacement = $lng->get()->fetchPairs(Language::DATA_ID, Language::DATA_NAME);
		$dataGrid->addColumn(Users::DATA_LANGUAGE, Locales::get()->get("language"))
			->addSelectboxFilter($lngReplacement);
		$dataGrid[Users::DATA_LANGUAGE]->replacement = $lngReplacement;

		$dataGrid->addActionColumn(Locales::get()->get("actions"));
		$dataGrid->addAction(
			Locales::get()->get("edit"),
			'edit',
			Html::el('span')->setText(Locales::get()->get("edit")), $useAjax = FALSE
		);
		// TODO: link user's profile
		$dataGrid->addAction(
			Locales::get("users")->get("profile"),
			':',
			Html::el('span')->setText(Locales::get("users")->get("profile")), $useAjax = FALSE
		);

		return $dataGrid;
	}

	protected function createUserInfoForm($name) {
		$form = new AppForm($this,$name);

		$roles = new Role();

		$form->addGroup(Locales::get("users")->get("edit_user"));

		$form->addHidden(Users::DATA_ID);

		$form->addSelect(
			Users::DATA_ROLE,
			Locales::get("users")->get("role"),
			$roles->get()->fetchPairs(Role::DATA_ID, Role::DATA_NAME)
		);

		$form->addSelect(
			Users::DATA_TYPE,
			Locales::get("users")->get("user_type"),
			array(
				Users::TYPE_COMMON => Locales::get("users")->get("user_type_common"),
				Users::TYPE_ROOT => Locales::get("users")->get("user_type_root")
			)
		);

		$form->addSubmit("userInfoSubmit", Locales::get()->get("edit"));
		$form->onSubmit[] = array($this, "editUserSubmitted");

		$form->setDefaults($this->updatedUser);

		return $form;
	}

}
