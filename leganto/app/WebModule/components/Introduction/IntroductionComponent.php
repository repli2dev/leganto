<?php
/**
 * The source file is subject to the license located on web
 * "http://code.google.com/p/preader/".
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (repli2dev@gmail.com)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 */

/**
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$Id$
 *
 */

class IntroductionComponent extends BaseComponent
{
    /**
     * @persistent
     * @var string
     */
    public $state = "default";

    public function render(){
	switch($this->state){
	    case "default":
		$template = $this->loadDefaultTemplate();
		break;
	    case "login":
		$template = $this->loadLoginTemplate();
		break;
	    case "signup":
		$template = $this->loadSignUpTemplate();
		break;
	}
	$template->render();
    }

    public function loadDefaultTemplate(){
	$template = $this->getTemplate();
	$template->hint = Leganto::help()->getSelector()->findRandomByCategory();
	return $template;
    }

    public function loadLoginTemplate(){
	$template = $this->getTemplate();
	$template->form = $this->getComponent("loginForm");
	return $template;
    }

    public function loadSignUpTemplate(){
	$template = $this->getTemplate();
	$template->form = $this->getComponent("signUpForm");
	return $template;
    }
    
    /** PROTECTED **/

    protected function createComponentLoginForm($name){
	$loginForm = new BaseForm;
	$loginForm->getElementPrototype()->setId("signup");
	$loginForm->addGroup("Log in");
	$loginForm->addText("nickname", "Nickname")
		->addRule(Form::FILLED,"Please fill the nickname.");
	$loginForm->addPassword("password", "Password")
		->addRule(Form::FILLED,"Please fill the password.");
	$loginForm->addSubmit("submitted", "Log in");
	$loginForm->onSubmit[] = array($this, "loginFormSubmitted");
	return $loginForm;
    }

    protected function createComponentSignUpForm($name){
	$signUpForm = new BaseForm;
	$signUpForm->addGroup("Sign Up");
	$signUpForm->getElementPrototype()->setId("signup");
	$builder = new SimpleFormBuilder(Leganto::users()->createEmpty(), $signUpForm);
	$builder->disableItem("sex");
	$builder->disableItem("birth_year");
	$form = $builder->buildForm();
	$form->addPassword("password2","Password again");
	$form->addSubmit("submitSignUp", "Register");
	$form->onSubmit[] = array($builder, "onSubmit");
	return $form;
    }


    /** HANDLERS **/
    public function loginFormSubmitted(Form $form){
	    
    }

        public function handleChangeState($state){
	switch($state){
	    case "default":
	    case "login":
	    case "signup":
		$this->state = $state;
		break;
	    default:
		throw new InvalidArgumentException("The state can be only defualt, login or signup");
	}
    }
}

