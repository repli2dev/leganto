<?php
class PostListComponent extends BaseListComponent
{

    private $discussed;

    private $type;

    private $enablePosting = TRUE;

    public function handleDelete($post) {
        $postEntity = Leganto::posts()->getSelector()->find($post);
	if (!Environment::getUser()->isAllowed(Resource::create($postEntity), Action::EDIT)) {
	    $this->unathorized();
	}
        if ($postEntity == null) {
            $this->getPresenter()->flashMessage(System::translate('The post can not be deleted.'), "error");
            return;
        }
        try {
            $discussion = Leganto::discussions()->getSelector()->find($postEntity->discussion);
            $postEntity->delete();
            $this->getPresenter()->flashMessage(System::translate("The post has been deleted."), "success");
            if ($discussion->discussionType == PostSelector::TOPIC && $discussion->numberOfPosts == 1) {
                $this->getPresenter()->flashMessage(System::translate("The post has been last in the topic, therefore the topic has been also deleted."), "success");
                $this->getPresenter()->redirect("Discussion:default");
            }
        }
        catch (Expcetion $e) {
            Debug::fireLog($e->getMesssage());
            $this->getPresenter()->flashMessage(System::translate('Unexpected error happened.'), "error");
        }
    }

    public function formSubmitted(Form $form) {
	if (!Environment::getUser()->isAllowed(Resource::create($postEntity), Action::INSERT)) {
	    $this->unathorized();
	}
        $values = $form->getValues();

        // Check whether discussed item and its type present
        if(empty($values["discussed"]) || empty($values["type"])) {
            $form->addError("Unexpected error has happened.");
            return;
        }

        // Insert the post
        $post   = Leganto::posts()->createEmpty();
        $post->user             = System::user()->getId();
        $post->discussed        = $values["discussed"];
        $post->discussionType   = $values["type"];
        $post->content          = $values["content"];
        $post->inserted         = new DateTime();
        $post->language         = System::user()->idLanguage;
        $post->persist();

        // Redirect
        $this->getPresenter()->flashMessage("The post has been successfuly sent.", "success");
        $this->getPresenter()->redirect("this");
    }

    public function setDiscussed($discussed, $type) {
        if (empty($discussed)) {
            throw new NullPointerException("Parameter [discussed] is empty.");
        }
        if (empty($type)) {
            throw new NullPointerException("Parameter [type] is empty.");
        }
        $this->discussed    = $discussed;
        $this->type         = $type;
    }

    public function disablePosting() {
	    $this->enablePosting = FALSE;
    }

    // ---- PROTECTED METHODS

    protected function beforeRender() {
	parent::beforeRender();
	$this->loadTemplate($this->getSource());
	$this->getTemplate()->enablePosting = $this->enablePosting;
    }


    protected function createComponentForm($name) {
        $form = new BaseForm($this, $name);

        $form->addTextArea("content")
            ->addRule(Form::FILLED, "Please fill the content.");

        $form->addSubmit("insertPost", "Send post");

        $form->addHidden("discussed");
        $form->addHidden("type");

        if (!empty($this->discussed)) {
            $form->setDefaults(array(
                "discussed" => $this->discussed,
                "type"      => $this->type
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
	$this->getTemplate()->posts = Leganto::posts()->fetchAndCreateAll($source);
    }


}
