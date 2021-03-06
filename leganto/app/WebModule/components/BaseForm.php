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
// FIXME: udelat kontrolu spamu lepe nez prez pri validaci ukladat obsah do session a redirectovat...

class BaseForm extends AppForm {

	public function __construct($parent = NULL, $name = NULL) {
		parent::__construct($parent, $name);
		// Do not use tables for forms any more
		self::tuneRenderer($this);
		$this->setTranslator(System::translator());
		// Add protection aganist cross site requests
		$this->addProtection("Form timeout, please send form again.");
	}

	public static function tuneRenderer($form) {
		// Do not use tables for forms any more
		$form->setRenderer(new BaseFormRenderer());
		$renderer = $form->getRenderer();
		$renderer->wrappers['controls']['container'] = NULL;
		$renderer->wrappers['pair']['container'] = 'p';
		$renderer->wrappers['label']['container'] = NULL;
		$renderer->wrappers['control']['container'] = NULL;
		// Workaround for using css selectors
		$renderer->wrappers['control']['.select'] = 'select';
		$renderer->wrappers['control']['.textarea'] = 'textarea';
		$renderer->wrappers['control']['.checkbox'] = 'checkbox';
		$renderer->wrappers['control']['.radio'] = 'radio';
		$renderer->wrappers['control']['.file'] = '';
		return $form;
	}

	public function render() {
		// Check if there are data in session
		$formName = $this->getName();
		if (empty($formName)) {
			throw new NullPointerException("Spam protection validation cannot work here, please check name of form.");
		}
		$session2 = Environment::getSession("Protection/FormValues/" . $formName);
		if (isSet($session2->values)) { // Data found, restore, unset and invoke error (wrong spam)
			$this->setValues($session2->values);
			unset($session2->values);
			$this->addError("Your answer is wrong, please try it again with a different question.");
		}
		parent::render();
	}

	public function addSpamProtection() {
		// Get form name to prepare session namespace
		$formName = $this->getName();
		if (empty($formName)) {
			throw new NullPointerException("Spam protection cannot be added to form without name, please fix it.");
		}

		// Generate and save ID of question to session (for check)
		$session = Environment::getSession("Protection/Spam/" . $formName);
		if (!isSet($session->captchaKey)) {
			$data = System::captchaQuestion();
			$session->captchaKey = $data->id_captcha;
		} else {
			$data = Leganto::captcha()->getSelector()->find($session->captchaKey);
		}
		$a = $this->addText("captchacode", $data->question, 10)
				->addRule(Form::FILLED, "Please answer the captcha question to let us know that you are a human.")
				->addCondition(Form::FILLED)
				->addRule("BaseForm::validateSpamProtection", "Your answer is wrong, please try it again with a different question.");
		$this["captchacode"]->getControlPrototype()->class("spamAnswer");
	}

	public static function validateSpamProtection(TextInput $input) {
		// Find id of question in session
		$formName = $input->getForm()->getName();
		if (empty($formName)) {
			throw new NullPointerException("Spam protection validation cannot work here, please check name of form.");
		}
		$session = Environment::getSession("Protection/Spam/" . $formName);

		$captchaCode = $input->getValue();
		$data = Leganto::captcha()->getSelector()->find($session->captchaKey);
		if (strtolower($captchaCode) != $data->answer) {
			// Prepare for new question
			$input->setValue("");
			// Unset question
			unset($session->captchaKey);
			// Save form and redirect to change the question
			$session2 = Environment::getSession("Protection/FormValues/" . $formName);
			$session2->values = $input->getForm()->getValues();
			$input->getForm()->presenter->redirect("this");
			return FALSE;
		} else {
			unset($session->captchaKey);
			return TRUE;
		}
	}

}
