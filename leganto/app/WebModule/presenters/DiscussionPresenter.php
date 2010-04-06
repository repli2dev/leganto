<?php
class Web_DiscussionPresenter extends Web_BasePresenter
{

    public function renderDefault() {
	$this->setPageTitle(System::translate("Discussion topics"));
	$this->getComponent("topicList")->setUp(Leganto::topics()->getSelector()->findAll()->orderBy("inserted", "desc"));
    }

    public function renderPosts($discussion) {
	$discussionEntity = Leganto::discussions()->getSelector()->find($discussion);
	$this->setPageTitle($discussionEntity->name);
	$this->getTemplate()->discussion = $discussionEntity;
	$this->getComponent("postList")->setUp(
	    Leganto::posts()->getSelector()->findAllByIdAndType(
		$discussionEntity->discussed,
		$discussionEntity->discussionType
	    )->orderBy("inserted", "desc")
	);
    }

    protected function createComponentTopicList($name) {
	return new TopicListComponent($this, $name);
    }

    protected function createComponentPostList($name) {
	return new PostListComponent($this, $name);
    }
}