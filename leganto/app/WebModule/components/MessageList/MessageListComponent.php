<?php

/**
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
class MessageListComponent extends BaseListComponent {

	private $toUser;

	public function handleDelete($message) {
		throw new NotImplementedException();
	}

	public function formSubmitted(Form $form) {
		if (!Environment::getUser()->isAllowed(Resource::MESSAGE, Action::INSERT)) {
			$this->unathorized();
		}
		$values = $form->getValues();

		if(($user = Leganto::users()->getSelector()->findByNick($values["recipient"])) === NULL) {
			$form->addError("This user is unreachable. Check your spelling.");
			return;
		}
		$message = Leganto::messages()->createEmpty();
		$message->idUserFrom = System::user()->getId();
		$message->idUserTo = $user->getId();
		$message->read = 0;
		$message->content = $values["content"];
		$message->inserted = new DateTime();
			
		try {
			$message->persist();
			System::log("PRIVATE MESSAGE '" . $message->getId() . "' TO USER '".$user->getId()."'");
			$this->getPresenter()->flashMessage(System::translate("Your message has been successfuly sent."), "success");
		} catch (Exception $e) {
			$this->unexpectedError($e);
			return;
		}
		// Redirect
		$this->getPresenter()->redirect("this",null);
	}

	// ---- PROTECTED METHODS

	protected function beforeRender() {
		parent::beforeRender();
		$this->loadTemplate($this->getSource());
	}

	protected function createComponentForm($name) {
		$form = new BaseForm($this, $name);

		$form->addText('recipient',"Recipient")
			->addRule(Form::FILLED,"Please fill the recipient.")
			->setOption("description",System::translate("(Nickname of the user)"));
		$form->addTextArea("content","Content")
			->addRule(Form::FILLED, "Please fill the content.");

		$form->addSubmit("submitted", "Send message");

		$form->onSubmit[] = array($this, "formSubmitted");

		if(!empty($this->toUser)) {
			$values["recipient"] = $this->getUserEntity($this->toUser)->nickname;
			$form->setValues($values);
		}
		
		return $form;
	}

	public function getUserEntity($userId) {
		if (empty($userId)) {
			throw new NullPointerException("User id is null.");
		}
		$user = Leganto::users()->getSelector()->find($userId);
		return $user;
	}

	public function setRecipient($toUser) {
		if(empty($toUser)) {
			throw new NullPointerException("User id is empty.");
		}
		$this->toUser = $toUser;
	}

	// ---- PRIVATE METHODS

	private function loadTemplate(DibiDataSource $source) {
		$paginator = $this->getPaginator();
		if ($this->getLimit() == 0) {
			$this->getPaginator()->itemsPerPage = $paginator->itemCount;
		}
		$source->applyLimit($paginator->itemsPerPage, $paginator->offset);
		$this->getTemplate()->messages = Leganto::messages()->fetchAndCreateAll($source);
	}

}
