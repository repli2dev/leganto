<?php

/**
 * Discussions 
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace FrontModule;

use Leganto\DB\Factory,
    FrontModule\Components\TopicList,
    FrontModule\Components\PostList;

class DiscussionPresenter extends BasePresenter {

	public function renderDefault() {
		$this->setPageTitle($this->translate("Discussion topics"));
		$this->setPageDescription($this->translate("This is the list of all discussion topics which are currently discussed, feel free to join."));
		$this->setPageKeywords($this->translate("discussion topics, forum, threads"));
		$this->getComponent("topicList")->setSource(Factory::topic()->getSelector()->findAll()->orderBy("last_post_inserted", "desc"));
	}

	public function renderDiscussion($id) {
		$discussionEntity = Factory::discussion()->getSelector()->find($id);
		if (empty($discussionEntity)) {
			$this->flashMessage($this->translate("The discussion does not exist."), "error");
			$this->redirect("default");
		}
		$this->setPageTitle($this->translate("Discussion") . ": " . $discussionEntity->name);
		$this->setPageDescription($this->translate("This page shows one topic thread, where all posts are ordered by date, feel free to join."));
		$this->setPageKeywords($this->translate("discussion post, thread, forum"));
		$this->getTemplate()->discussion = $discussionEntity;
		$this->getComponent("postList")->setSource(
			Factory::post()->getSelector()->findAllByIdAndType(
				$discussionEntity->discussed, $discussionEntity->discussionType
			)->orderBy("inserted", "desc")
		);
		$this->getComponent("postList")->setDiscussed($discussionEntity->discussed, $discussionEntity->discussionType);
	}

	public function renderPosts($discussed, $type) {
		$discussion = Factory::discussion()->getSelector()->findByDiscussedAndType($discussed, $type);
		if (empty($discussion)) {
			$this->flashMessage($this->translate("The discussion does not exist."), "error");
			$this->redirect("default");
		}
		$this->setPageTitle($this->translate("Topic") . ": " . $discussion->name);
		$this->setPageDescription($this->translate("This page shows one topic thread, where all posts are ordered by date, feel free to join."));
		$this->setPageKeywords($this->translate("discussion post, thread, forum"));
		$this->getTemplate()->discussion = $discussion;
		$this->getComponent("postList")->setSource(
			Factory::post()->getSelector()->findAllByIdAndType(
				$discussed, $type
			)->orderBy("inserted", "desc")
		);
		$this->getComponent("postList")->showSorting(TRUE);
		$this->getComponent("postList")->setDiscussed($discussed, $type);
	}

	protected function createComponentTopicList($name) {
		return new TopicList($this, $name);
	}

	protected function createComponentPostList($name) {
		return new PostList($this, $name);
	}

}