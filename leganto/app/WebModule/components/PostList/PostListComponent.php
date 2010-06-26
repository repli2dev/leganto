<?php
class PostListComponent extends BaseListComponent
{

    private $discussed;

    private $type;

    public function formSubmitted(Form $form) {
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

    // ---- PROTECTED METHODS

    protected function beforeRender() {
	parent::beforeRender();
	$this->loadTemplate($this->getSource());
    }


    protected function createComponentForm($name) {
        $form = new BaseForm($name);

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
