<?php

/**
 * Tuned form for nearly all forms
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace FrontModule\Forms;

use Leganto\System;

class BaseForm extends \Nette\Application\UI\Form {

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
		$form->setRenderer(new BaseRenderer());
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

}
