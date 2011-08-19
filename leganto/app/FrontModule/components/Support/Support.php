<?php

/**
 * Support form
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace FrontModule\Components;

use FrontModule\Forms\BaseForm,
    Nette\Forms\Form,
    Leganto\Templating\Helpers,
    Nette\DateTime;

class Support extends BaseComponent {
	
	private $error;
	
	public function render($error = FALSE) {
		$this->error = $error;
		$this->getTemplate()->error = $this->error;
		parent::render();
	}

	public function createComponentSupportForm($name) {
		// Enable text captcha (the only usage)
		\Nette\Forms\Container::extensionMethod('addTextCaptcha', array('\TextCaptcha\TextCaptcha', 'addTextCaptcha'));
		\TextCaptcha\TextCaptcha::setSession($this->getContext()->getService("session"));
		\TextCaptcha\TextCaptcha::setBackend(new \TextCaptcha\DatabaseBackend($this->getContext()->getService("database")));
		\TextCaptcha\TextCaptcha::setLanguage($this->getContext()->getService("environment")->domain()->idLanguage);
		// Form
		$content = $this->translate("1. Description of situation (what is not working)")."\n\n\n".$this->translate("2. Steps to reproduce")."\n\n\n".$this->translate("3. Expected behaviour")."\n";
		$form = new BaseForm($this,$name);
		$form->addText("mail","Your e-mail")
			->setRequired("Please fill your e-mail.")
			->addRule(Form::EMAIL,"Please fill your e-mail like someone@somewhere.tld.");
		$form->addTextArea("content", "Description" ,50 , 30)
			->addRule(~Form::EQUAL,"Please specify your description.",$content)
			->setRequired("Please fill description of your problem or situation.")
			->getControlPrototype()->class("support-text");
		$form->addTextCaptcha();
		$form->addSubmit("submitted","Odeslat");
		$form->onSuccess[] = array($this,"supportFormSubmitted");
		
		if($this->error) {
			$form->setDefaults(array("content" => $content));
		}
		return $form;
	}
	
	public function supportFormSubmitted(Form $form) {
		$values = $form->getValues();
		$mail = Helpers::getMailPrototype();
		$mail->addTo($this->getContext()->params["mail"]["info"], $this->getContext()->params["mail"]["name"]);
		$mail->setFrom($values["mail"]);
		if($this->error) {
			$mail->setSubject($this->getContext()->getService("environment")->domain()->uri.": ".$this->translate("Error report"));
		} else {
			$mail->setSubject($this->getContext()->getService("environment")->domain()->uri.": ".$this->translate("Contact form"));
		}
		$mail->setBody($this->translate("Message from website")."\n\nDate and time: ".date("d. m. Y H:i")."\nIP: ".$_SERVER["REMOTE_ADDR"]."\nBrowser info: ".$_SERVER['HTTP_USER_AGENT']."\n\nContent: \n".$values["content"]);
		$mail->send();
		$this->getPresenter()->flashMessage($this->translate("Thank you. Your message has been sent. We reply on your e-mail as soon as possible."));
		$this->redirect("this");
	}

}