<?php
/**
 * This class provides manipulation with user's status.
 *
 * @author Jan Papousek
 */
class UserStatusComponent extends BaseControl
{
	
	/** @persistent */
	public $formType; // 'new'/'edit'

	/** @persistent */
	public $updatedId;

	/** @persistent */
	public $limit;

    public function render() {
		$template = $this->createTemplate();
		
		$template->setFile(TEMPLATES_DIR . '/UsersModule/components/userStatus.phtml');

		if (Environment::getUser()->isAuthenticated()) {
			$template->form = $this->getComponent("statusForm");
		}

		if (empty($this->limit)) {
			$this->limit = 20;
		}

		$status = new Status();
		$template->states = $status->get()
			->orderBy(Status::DATA_INSERTED, "desc")
			->applyLimit($this->limit, 0);

		$template->limit = $this->limit;

		$template->render();
	}

	public function handleUpdate($id_status) {
		$this->formType = "edit";
		$this->updatedId = $id_status;
	}

	public function handleLimit($limit) {
		$this->limit = $limit;
	}

	/**
	 * @Secured(action=insert)
	 */
	public function insertSubmitted(Form $form) {
		$values = $form->getValues();
		$status = new Status();
		try {
			$values[Status::DATA_USER] = Environment::getUser()->getIdentity()->id_user;
			$values[Status::DATA_LANGUAGE] = Environment::getUser()->getIdentity()->id_language;
			$status->insert($values);
			$this->getPresenter()->flashMessage(Locales::get("users")->get("status_inserted"));
			$this->getPresenter()->redirect($this->getPresenter()->getAction());
		}
		catch (DibiDriverException $e) {
			$form->addError(Locales::get()->get("database_error"));
			Debug::processException($e);
		}
	}

	/**
	 * @Secured(action=edit)
	 */
	public function editSubmitted(Form $form) {
		$values = $form->getValues();
		$status = new Status();
		try {
			if (!empty($values["delete"])) {
				$status->delete($this->updatedId);
			}
			else {
				unset($values["delete"]);
				$status->update($this->updatedId, $values);
			}
			$this->getPresenter()->flashMessage(Locales::get("users")->get("status_updated"));
			$this->formType = "new";
			$this->getPresenter()->redirect($this->getPresenter()->getAction());
		}
		catch (DibiDriverException $e) {
			$form->addError(Locales::get()->get("database_error"));
			Debug::processException($e);
		}
	}
		

	/* COMPONENTS */
	protected function createStatusForm($name) {
		$form = new AppForm($this, $name);

		if (empty($this->formType) ||$this->formType == "new") {
			$form->addGroup(Locales::get("users")->get("insert_status"));
		}
		else {
			$form->addGroup(Locales::get("users")->get("edit_status"));
		}

		$form->addTextArea(Status::DATA_CONTENT, Locales::get("users")->get("status"))
			->addRule(Form::FILLED, Locales::get("users")->get("status_not_filled"));

		if (empty($this->formType) ||$this->formType == "new") {
			$form->addSubmit("statusSubmit", Locales::get()->get("insert"));
			$form->onSubmit[] = array($this,"insertSubmitted");
		}
		else {
			$form->addCheckbox("delete", Locales::get()->get("delete"));
			$form->addSubmit("statusSubmit", Locales::get()->get("edit"));
			$form->onSubmit[] = array($this,"editSubmitted");
			
			$status = new Status();
			$form->setDefaults(
				$status->get()->where("%n = %i",Status::DATA_ID,$this->updatedId)->fetch()
			);
		}

		return $form;
	}

}

