<?php

/**
 * Components with list of posts
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
    Leganto\DB\Post\Selector,
    Nette\Environment,
    DibiDataSource,
    InvalidArgumentException,
    FrontModule\Forms\BaseForm,
    Nette\Forms\Form,
    Nette\Utils\Html,
    Exception;

class PostList extends BaseListComponent {

	private $discussed;
	private $type;
	private $enablePosting = TRUE;
	private $enableLinks = FALSE;

	/**
	 * Change sorting to sorting by time
	 */
	public function handleSortByTime() {
		$this->sort("inserted");
	}

	/**
	 * Delete post if user is allowed
	 * @param \Leganto\DB\Post\Entity $post Post to be deleted
	 * @return void
	 */
	public function handleDelete($post) {
		$postEntity = Factory::post()->getSelector()->find($post);
		if (!$this->getUser()->isAllowed(Resource::create($postEntity), Action::EDIT)) {
			$this->unathorized();
		}
		if ($postEntity == null) {
			$this->getPresenter()->flashMessage($this->translate("The post can't be deleted."), "error");
			return;
		}
		try {
			$discussion = Factory::discussion()->getSelector()->find($postEntity->discussion);
			$postEntity->delete();
			$this->getContext()->getService("logger")->log("DELETE POST '" . $postEntity->getId() . "'");
			$this->getPresenter()->flashMessage($this->translate("The post has been deleted."), "success");
			if ($discussion->discussionType == Selector::TOPIC && $discussion->numberOfPosts == 1) {
				$this->getPresenter()->flashMessage($this->translate("The post has been the last one in the topic, therefore the topic has been also deleted."), "success");
				$this->getPresenter()->redirect("Discussion:default");
			}
		} catch (Expcetion $e) {
			$this->unexpectedError($e);
		}
	}

	/**
	 * Add new post to discussion
	 * @param Form $form
	 * @return void
	 */
	public function formSubmitted(Form $form) {
		if (!$this->getUser()->isAllowed(Resource::POST, Action::INSERT)) {
			$this->unathorized();
		}
		$values = $form->getValues();

		// Check whether discussed item and its type present
		if (empty($values["discussed"]) || empty($values["type"])) {
			$form->addError("An unexpected error has occurred.", "error");
			return;
		}

		// Insert the post
		$post = Factory::post()->createEmpty();
		$post->user = $this->getUserEntity()->getId();
		$post->discussed = $values["discussed"];
		$post->discussionType = $values["type"];
		$post->content = $values["content"];
		$post->inserted = new DateTime();
		$post->language = $this->getUserEntity()->idLanguage;
		try {
			$post->persist();
			$this->getContext()->getService("logger")->log("INSERT POST '" . $post->getId() . "'");
			$this->getPresenter()->flashMessage($this->translate("The post has been successfuly sent."), "success");
		} catch (Exception $e) {
			$this->unexpectedError($e);
			return;
		}
		// Redirect
		$this->getPresenter()->redirect("this");
	}

	public function setDiscussed($discussed, $type) {
		if (empty($discussed)) {
			throw new InvalidArgumentException("Parameter [discussed] is empty.");
		}
		if (empty($type)) {
			throw new InvalidArgumentException("Parameter [type] is empty.");
		}
		$this->discussed = $discussed;
		$this->type = $type;
	}

	/**
	 * Disable posting of new posts
	 */
	public function disablePosting() {
		$this->enablePosting = FALSE;
	}

	/**
	 * Show link to discussion (used in search)
	 */
	public function enableLinks() {
		$this->enableLinks = TRUE;
	}

	/**
	 * Toogle showing of sorting options.
	 * @param boolean $show
	 */
	public function showSorting($show = TRUE) {
		$this->getTemplate()->sorting = TRUE;
	}

	// ---- PROTECTED METHODS

	protected function beforeRender() {
		parent::beforeRender();
		$this->loadTemplate($this->getSource());
		$this->getTemplate()->enablePosting = $this->enablePosting;
		$this->getTemplate()->enableLinks = $this->enableLinks;
	}

	protected function createComponentForm($name) {
		$form = new BaseForm($this, $name);

		// Prepare text
		$container = Html::el("span")->class("see-help");
		$container->add(Html::el()->setText($this->translate("You can use texy for formatting, emoticons or links to other books.")) . " ");
		$container->add(Html::el("a")->href($this->presenter->link("Help:text", 64))->setText($this->translate("See help")));
		$container->add(Html::el()->setText("."));

		$form->addTextArea("content")
			->setRequired("Please fill the content.")
			->setOption("description", $container);

		$form->addSubmit("insertPost", "Send post");

		$form->addHidden("discussed");
		$form->addHidden("type");

		if (!empty($this->discussed)) {
			$form->setDefaults(array(
			    "discussed" => $this->discussed,
			    "type" => $this->type
			));
		}

		$form->onSuccess[] = array($this, "formSubmitted");
		return $form;
	}

	// ---- PRIVATE METHODS

	private function loadTemplate(DibiDataSource $source) {
		$paginator = $this->getPaginator();
		if ($this->getLimit() == 0) {
			$this->getPaginator()->itemsPerPage = $paginator->itemCount;
		}
		$source->applyLimit($paginator->itemsPerPage, $paginator->offset);
		$this->getTemplate()->posts = array();
		$userIds = array();
		while ($post = Factory::post()->fetchAndCreate($source)) {
			$this->getTemplate()->posts[] = $post;
			$userIds[] = $post->user;
		}
		if (count($userIds) != 0) {
			$this->getTemplate()->achievements = Factory::achievement()->getSelector()->findByUsers($userIds, $entities = FALSE);
		}
	}

}
