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
class Web_DiscussionPresenter extends Web_BasePresenter {

	public function renderDefault() {
		$this->setPageTitle(System::translate("Discussion topics"));
		$this->setPageDescription(System::translate("This is the list of all discussion topics which are currently discussed, feel free to join."));
		$this->setPageKeywords(System::translate("discussion topics, forum, threads"));
		$this->getComponent("topicList")->setSource(Leganto::topics()->getSelector()->findAll()->orderBy("last_post_inserted", "desc"));
	}

	public function renderDiscussion($id) {
		$discussionEntity = Leganto::discussions()->getSelector()->find($id);
		if (empty($discussionEntity)) {
			$this->flashMessage(System::translate("The discussion does not exist."), "error");
			$this->redirect("default");
		}
		$this->setPageTitle(System::translate("Discussion") . ": " . $discussionEntity->name);
		$this->setPageDescription(System::translate("This page shows one topic thread, where all posts are ordered by date, feel free to join."));
		$this->setPageKeywords(System::translate("discussion post, thread, forum"));
		$this->getTemplate()->discussion = $discussionEntity;
		$this->getComponent("postList")->setSource(
			Leganto::posts()->getSelector()->findAllByIdAndType(
				$discussionEntity->discussed,
				$discussionEntity->discussionType
			)->orderBy("inserted", "desc")
		);
		$this->getComponent("postList")->setDiscussed($discussionEntity->discussed, $discussionEntity->discussionType);
	}

	public function renderPosts($discussed, $type) {
		$discussion = Leganto::discussions()->getSelector()->findByDiscussedAndType($discussed, $type);
		if (empty($discussion)) {
			$this->flashMessage(System::translate("The discussion does not exist."), "error");
			$this->redirect("default");
		}
		$this->setPageTitle(System::translate("Topic") . ": " . $discussion->name);
		$this->setPageDescription(System::translate("This page shows one topic thread, where all posts are ordered by date, feel free to join."));
		$this->setPageKeywords(System::translate("discussion post, thread, forum"));
		$this->getTemplate()->discussion = $discussion;
		$this->getComponent("postList")->setSource(
			Leganto::posts()->getSelector()->findAllByIdAndType(
				$discussed,
				$type
			)->orderBy("inserted", "desc")
		);
		$this->getComponent("postList")->showSorting(TRUE);
		$this->getComponent("postList")->setDiscussed($discussed, $type);
	}

	protected function createComponentTopicList($name) {
		return new TopicListComponent($this, $name);
	}

	protected function createComponentPostList($name) {
		return new PostListComponent($this, $name);
	}

}