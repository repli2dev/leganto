<?php

/**
 * List of discussion topics
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */

namespace FrontModule\Components;

use Nette\Environment,
    Leganto\DB\Factory,
    FrontModule\Forms\BaseForm,
    Nette\Forms\Form,
    Leganto\ACL\Resource,
    Leganto\ACL\Action,
    Nette\DateTime,
    Leganto\DB\Post\Selector;

class TopicList extends BaseListComponent {

	protected function beforeRender() {
		parent::beforeRender();
		$paginator = $this->getPaginator();
		$this->getSource()->applyLimit($paginator->itemsPerPage, $paginator->offset);
		$this->getTemplate()->topics = Factory::topic()->fetchAndCreateAll($this->getSource());
	}

	/**
	 * Add new topic and first post
	 * @param Form $form
	 */
	public function formSubmitted(Form $form) {
		if (!$this->getUser()->isAllowed(Resource::TOPIC, Action::INSERT)) {
			$this->unauthorized();
		}
		$values = $form->getValues();
		$logger = $this->getContext()->getService("logger");

		// Inserting of topic
		$topic = Factory::topic()->createEmpty();
		$topic->name = $values["name"];
		$topic->userId = $this->getUser()->getId();
		$topic->inserted = new DateTime();
		try {
			$topic->persist();
			$logger->log("INSERT TOPIC '" . $topic->getId() . "'");

			// Inserting of post
			$post = Factory::post()->createEmpty();
			$post->user = $this->getUser()->getId();
			$post->discussed = $topic->getId();
			$post->discussionType = Selector::TOPIC;
			$post->content = $values["content"];
			$post->inserted = new DateTime();
			$post->language = $this->getUserEntity()->idLanguage;
			$post->persist();
			$logger->log("INSERT POST '" . $post->getId() . "");
			$this->getPresenter()->flashMessage("The topic has been successfuly created.", "success");
		} catch (Exception $e) {
			$this->unexpectedError($e);
			return;
		}
		// Redirect
		$this->getPresenter()->redirect($this->getPresenter()->backlink());
	}
	
	protected function createComponentForm($name) {
		$form = new BaseForm($this, $name);

		$form->addText("name", "Topic name")
			->setRequired(Form::FILLED, "Please fill the topic name.");
		$form->addTextArea("content", "Content")
			->setRequired(Form::FILLED, "Please fill the content.");

		$form->addSubmit("insertTopic", "Create a new topic");

		$form->onSuccess[] = array($this, "formSubmitted");
		return $form;
	}

}
