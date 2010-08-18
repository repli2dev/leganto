<?php
class TopicListComponent extends BaseListComponent
{

    protected function beforeRender() {
	parent::beforeRender();
	$paginator = $this->getPaginator();
	$this->getSource()->applyLimit($paginator->itemsPerPage, $paginator->offset);
	$this->getTemplate()->topics = Leganto::topics()->fetchAndCreateAll($this->getSource());
    }

    public function formSubmitted(Form $form) {
        $values = $form->getValues();

        // Inserting of topic
        $topic  = Leganto::topics()->createEmpty();
        $topic->name        = $values["name"];
        $topic->userId      = System::user()->getId();
        $topic->inserted    = new DateTime();
        $topic->persist();
	System::log("INSERT TOPIC '".$topic->getId()."'");

        // Inserting of post
        $post   = Leganto::posts()->createEmpty();
        $post->user             = System::user()->getId();
        $post->discussed        = $topic->getId();
        $post->discussionType   = PostSelector::TOPIC;
        $post->content          = $values["content"];
        $post->inserted         = new DateTime();
        $post->language         = System::user()->idLanguage;
        $post->persist();
	System::log("INSERT POST '".$post->getId()."");

        // Redirect
        $this->getPresenter()->flashMessage("The topic has been successfuly created.", "success");
        $this->getPresenter()->redirect($this->getPresenter()->backlink());
    }

    protected function createComponentForm($name) {
        $form = new BaseForm($this, $name);

        $form->addText("name", "Topic name")
            ->addRule(Form::FILLED, "Please fill the topic name.");
        $form->addTextArea("content", "Content")
            ->addRule(Form::FILLED, "Please fill the content.");

        $form->addSubmit("insertTopic", "Create a new topic");

        $form->onSubmit[] = array($this, "formSubmitted");
        return $form;
    }

}
