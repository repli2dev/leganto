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

class BaseForm extends AppForm
{
	/** @persistent */
	public $captchaKey;

	public function  __construct() {
		parent::__construct();
		// Do not use tables for forms any more
		$renderer = $this->getRenderer();
		$renderer->wrappers['controls']['container'] = NULL;
		$renderer->wrappers['pair']['container'] = 'p';
		$renderer->wrappers['label']['container'] = NULL;
		$renderer->wrappers['control']['container'] = NULL;
		$this->setTranslator(System::translator());
		// Add protection aganist cross site requests
		$this->addProtection("Form timeout, please send form again.");
	}

	public function addSpamProtection() {
		$data = System::captchaQuestion();
		Debug::dump($data);
		if(empty($this->captchaKey)) {
			$this->captchaKey = $data->id_captcha;
		}
		echo $this->captchaKey;
		$this->addText("captchacode",$data->question)
			->addRule(Form::FILLED,"Please answer to captcha question to ensure that you are human.")
			->addCondition(Form::FILLED)
				->addRule("BaseForm::validateSpamProtection","Your answer is wrong, please try it again with defferent question.");
	}

	public static function validateSpamProtection(TextInput $input) {
		$captchaKey = $input->getForm()->captchaKey;
		$captchaCode = $input->getValue();
		$data = Leganto::captcha()->getSelector()->find($captchaKey);
		Debug::dump($data);
		if(strtolower($captchaCode) != $data->answer) {
			$input->setValue("");
			return FALSE;
		} else {
			return TRUE;
		}
	}

}
