<?php
class Web_DiscussionPresenter extends Web_BasePresenter
{

    public function renderDefault() {
	$this->setPageTitle(System::translate("Discussion topics"));
	$this->getComponent("topicList")->setSource(Leganto::topics()->getSelector()->findAll()->orderBy("inserted", "desc"));
    }

    public function renderPosts($topic) {
	$topicEntity = Leganto::discussions()->getSelector()->find($topic);
	$this->setPageTitle($topicEntity->name);
	$this->getTemplate()->topic = $topicEntity;
	$this->getComponent("postList")->setSource(
	    Leganto::posts()->getSelector()->findAllByIdAndType(
		$topic,
		PostSelector::TOPIC
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