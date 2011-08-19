<?php

/**
 * Message list
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace FrontModule\Components;

use Leganto\DB\Factory,
    Leganto\ACL\Resource,
    Leganto\ACL\Action,
    Nette\DateTime,
    Exception,
    FrontModule\Forms\BaseForm,
    Nette\Forms\Form,
    InvalidArgumentException,
    DibiDataSource;

class MessageList extends BaseListComponent {

	private $toUser;
	
	public function handleReplyTo($user) {
		if ($this->getContext()->httpRequest->isAjax()) {
			$this->setRecipient($user);
			$this->invalidateControl("messageForm");
		} else {
			$this->getPresenter()->redirect("User:messages",$user);
		}
	}

	public function handleDelete($message) {
		if (empty($message)) {
			$this->getPresenter()->flashMessage($this->translate("Message you are trying to delete does not exist."), "error");
			$this->getPresenter()->redirect("this");
			return;
		}
		$entity = Factory::message()->getSelector()->find($message);
		if ($entity == NULL || !$this->getUser()->isAllowed(Resource::create($entity), Action::EDIT)) {
			$this->getPresenter()->flashMessage($this->translate("Message you are trying to delete does not exist."), "error");
			$this->getPresenter()->redirect("this");
			return;
		}
		$user = $this->getUserEntity();
		if ($entity->idUserFrom == $user->getId()) {
			$entity->deletedByOwner = 1;
		}
		if ($entity->idUserTo == $user->getId()) {
			$entity->deletedByRecipient = 1;
		}
		// Both decided to delete -> delete
		if ($entity->deletedByOwner == 1 && $entity->deletedByRecipient == 1) {
			$entity->delete();
		} else {
			$entity->persist();
		}
		$this->getPresenter()->flashMessage($this->translate("Message has been successfuly deleted."), "success");
		$this->getPresenter()->redirect("this");
	}

	public function formSubmitted(Form $form) {
		if (!$this->getUser()->isAllowed(Resource::MESSAGE, Action::INSERT)) {
			$this->unathorized();
		}
		$values = $form->getValues();

		if (($user = Factory::user()->getSelector()->findByNick($values["recipient"])) === NULL) {
			$form->addError("This user is unreachable. Check your spelling.");
			return;
		}
		$message = Factory::message()->createEmpty();
		$message->idUserFrom = $this->getUser()->getId();
		$message->idUserTo = $user->getId();
		$message->read = 0;
		$message->content = $values["content"];
		$message->inserted = new DateTime();

		$logger = $this->getContext()->getService("logger");
		try {
			$message->persist();
			$logger->log("PRIVATE MESSAGE '" . $message->getId() . "' TO USER '" . $user->getId() . "'");
			$this->getPresenter()->flashMessage($this->translate("Your message has been successfuly sent."), "success");
		} catch (Exception $e) {
			$this->unexpectedError($e);
			return;
		}
		// Redirect
		$this->getPresenter()->redirect("this", null);
	}

	// ---- PROTECTED METHODS

	protected function beforeRender() {
		parent::beforeRender();
		$this->loadTemplate($this->getSource());
	}

	protected function createComponentForm($name) {
		$form = new BaseForm($this, $name);

		$form->addText('recipient', "Recipient")
			->addRule(Form::FILLED, "Please fill the recipient.")
			->setOption("description", $this->translate("(Nickname of the user)"));
		$form->addTextArea("content", "Content")
			->addRule(Form::FILLED, "Please fill the content.");

		$form->addSubmit("submitted", "Send message");

		$form->onSuccess[] = array($this, "formSubmitted");

		if (!empty($this->toUser)) {
			$values["recipient"] = Factory::user()->getSelector()->find($this->toUser)->nickname;
			$form->setValues($values);
		}

		return $form;
	}

	public function setRecipient($toUser) {
		if (empty($toUser)) {
			throw new InvalidArgumentException("User id is empty.");
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
		$this->getTemplate()->messages = Factory::message()->fetchAndCreateAll($source);
		Factory::message()->getUpdater()->markRead($paginator->itemsPerPage, $paginator->offset,parent::getUserEntity());
	}

}
