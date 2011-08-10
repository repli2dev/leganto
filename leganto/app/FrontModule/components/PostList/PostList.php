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
namespace FrontModule\Components;
use Leganto\DB\Factory,
 Leganto\ACL\Resource,
 Leganto\DB\Post\Selector,
	Leganto\System,
	Nette\Environment,
	DibiDataSource,
	InvalidArgumentException;
class PostList extends BaseListComponent {

	private $discussed;
	private $type;
	private $enablePosting = TRUE;
	private $enableLinks = FALSE;

	public function handleSortByTime() {
		$this->sort("inserted");
	}

	public function handleDelete($post) {
		$postEntity = Factory::post()->getSelector()->find($post);
		if (!Environment::getUser()->isAllowed(Resource::create($postEntity), Action::EDIT)) {
			$this->unathorized();
		}
		if ($postEntity == null) {
			$this->getPresenter()->flashMessage(System::translate("The post can't be deleted."), "error");
			return;
		}
		try {
			$discussion = Factory::discussion()->getSelector()->find($postEntity->discussion);
			$postEntity->delete();
			System::log("DELETE POST '" . $postEntity->getId() . "'");
			$this->getPresenter()->flashMessage(System::translate("The post has been deleted."), "success");
			if ($discussion->discussionType == Selector::TOPIC && $discussion->numberOfPosts == 1) {
				$this->getPresenter()->flashMessage(System::translate("The post has been the last one in the topic, therefore the topic has been also deleted."), "success");
				$this->getPresenter()->redirect("Discussion:default");
			}
		} catch (Expcetion $e) {
			$this->unexpectedError($e);
		}
	}

	public function formSubmitted(Form $form) {
		if (!Environment::getUser()->isAllowed(Resource::POST, Action::INSERT)) {
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
		$post->user = System::user()->getId();
		$post->discussed = $values["discussed"];
		$post->discussionType = $values["type"];
		$post->content = $values["content"];
		$post->inserted = new DateTime();
		$post->language = System::user()->idLanguage;
		try {
			$post->persist();
			System::log("INSERT POST '" . $post->getId() . "'");
			$this->getPresenter()->flashMessage(System::translate("The post has been successfuly sent."), "success");
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

	public function disablePosting() {
		$this->enablePosting = FALSE;
	}

	public function enableLinks() {
		$this->enableLinks = TRUE;
	}

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
		$container->add(Html::el()->setText(System::Translate("You can use texy for formatting, emoticons or links to other books."))." ");
		$container->add(Html::el("a")->href($this->presenter->link("Help:text",64))->setText(System::translate("See help")));
		$container->add(Html::el()->setText("."));

		$form->addTextArea("content")
			->addRule(Form::FILLED, "Please fill the content.")
			->setOption("description",$container);

		$form->addSubmit("insertPost", "Send post");

		$form->addHidden("discussed");
		$form->addHidden("type");

		if (!empty($this->discussed)) {
			$form->setDefaults(array(
			    "discussed" => $this->discussed,
			    "type" => $this->type
			));
		}

		$form->onSubmit[] = array($this, "formSubmitted");
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
		if(count($userIds) != 0) {
			$this->getTemplate()->achievements = Factory::achievement()->getSelector()->findByUsers($userIds, $entities = FALSE);
		}
	}

}
