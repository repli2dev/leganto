<?php
class Web_DiscussionPresenter extends Web_BasePresenter
{

    public function renderDefault() {
	$this->setPageTitle(System::translate("Discussion topics"));
	$this->getComponent("topicList")->setSource(Leganto::topics()->getSelector()->findAll()->orderBy("inserted", "desc"));
    }

    public function renderPosts($discussed, $type) {
	$discussion = Leganto::discussions()->getSelector()->findByDiscussedAndType($discussed, $type);
	$this->setPageTitle($discussion->name);
	$this->getTemplate()->discussion = $discussion;
	$this->getComponent("postList")->setSource(
	    Leganto::posts()->getSelector()->findAllByIdAndType(
		$discussed,
		PostSelector::TOPIC
	    )->orderBy("inserted", "desc")
	);
        $this->getComponent("postList")->setDiscussed($discussed, $type);
    }

    protected function createComponentTopicList($name) {
	return new TopicListComponent($this, $name);
    }

    protected function createComponentPostList($name) {
	return new PostListComponent($this, $name);
    }
}