<?php
/**
 * This component provides sending and reading messages.
 *
 * @author Jan Papousek
 */
class UserMessagesComponent extends BaseControl
{

	/**
	 * It renders alert of new message.
	 */
	public function render() {
		$msg = new Message();
		try {
			if ($msg->getNotRead(Environment::getUser()->getIdentity()->id_user)->count() != 0) {
				$this->getPresenter()->flashMessage(Locales::get("users")->get("new_message_in_box"));
			}
		}
		catch (DibiDriverException $e) {
			$form->addError(Locales::get()->get("database_error"));
			Debug::processException($e);
		}
	}

	public function renderAll() {
		$template = $this->createTemplate();
		$template->setFile(TEMPLATES_DIR . '/UsersModule/components/userMessages.phtml');

		$msg = new Message();

		$template->form = $this->getComponent("messageForm");
		$template->messages = $msg->get()->where(
			"%n = %i OR %n = %i",
			Message::VIEW_FROM_ID,
			Environment::getUser()->getIdentity()->id_user,
			Message::VIEW_TO_ID,
			Environment::getUser()->getIdentity()->id_user
		)->fetchAll();

		$template->reply = Locales::get()->get("reply");
		$template->delete = Locales::get()->get("delete");

		$template->render();
	}

	public function handleDelete($id_message) {
		if (!Environment::getUser()->isAuthenticated()) {
			return;
		}
		$msg = new Message();
		try {
			$msg->markAsDeleted(
				$id_message,
				Environment::getUser()->getIdentity()->id_user
			);
			$this->getPresenter()->flashMessage(Locales::get("users")->get("msg_successfully_deleted"));
		}
		catch (DibiDriverException $e) {
			$form->addError(Locales::get()->get("database_error"));
			Debug::processException($e);
		}
	}

	public function messageSubmitted(Form $form) {
		$values = $form->getValues();
		$msg = new Message();
		$users = new Users();
		try {
			$toRows = $users->get()
				->where("%n = %s", Users::DATA_NICKNAME, $values[Message::DATA_USER_TO]);
			if ($toRows->count() == 0) {
				$this->presenter->flashMessage(Locales::get("users")->get("user_not_found"), "error");
				return;
			}
			$to = $toRows->fetch();
			$values[Message::DATA_USER_TO] = $to[Users::DATA_ID];
			$values[Message::DATA_USER_FROM] = Environment::getUser()->getIdentity()->id_user;
			$values[Message::DATA_INSERTED] = new DibiVariable("now()", "sql");
			$msg->insert($values);
			$this->getPresenter()->flashMessage(Locales::get("users")->get("message_sent"), "success");
			$this->getPresenter()->redirect(":Users:Frontend:messages");
		}
		catch (DibiDriverException $e) {
			$form->addError(Locales::get()->get("database_error"));
			Debug::processException($e);
		}
	}


	/* COMPONENTS */
	protected function createMessageForm($name) {
		$form = new AppForm($this, $name);

		$form->addText(Message::DATA_USER_TO, Locales::get("users")->get("msg_user_to"))
			->addRule(Form::FILLED, Locales::get("users")->get("msg_user_to_not_filled"));

		$form->addText(Message::DATA_SUBJECT, Locales::get("users")->get("msg_subject"))
			->addRule(Form::FILLED, Locales::get("users")->get("msg_subject_not_filled"));

		$form->addTextArea(Message::DATA_CONTENT, Locales::get("users")->get("message"))
			->addRule(Form::FILLED, Locales::get("users")->get("msg_not_filled"));

		$form->addSubmit("messageSubmit", Locales::get("users")->get("send_message"));
		$form->onSubmit[] = array($this, "messageSubmitted");

		return $form;
	}

}
