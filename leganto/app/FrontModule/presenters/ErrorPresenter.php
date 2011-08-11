<?php

/**
 * Error presenter to handle 403, 404, 500, 
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace FrontModule;

use Nette\Diagnostics\Debugger;
use Nette\Application\BadRequestException;

class ErrorPresenter extends BasePresenter {

	/**
	 * @param  Exception
	 * @return void
	 */
	public function renderDefault($exception) {
		$this->getTemplate()->robots = false;
		if ($this->isAjax()) { // AJAX request? Just note this error in payload.
			$this->payload->error = TRUE;
			$this->terminate();
		} elseif ($exception instanceof BadRequestException) {
			$this->setPageTitle($this->translate("404 Page not found"));
			$code = $exception->getCode();
			if($code == 403) { // load template 404.latte or 403.latte
				$this->setView("403");
			} else {
				$this->setView("404"); 
			}
		} else {
			$this->setPageTitle($this->translate("500 Server error"));
			$this->setView('500'); // load template 500.latte
			Debugger::processException($exception); // and handle error by Nette\Debug
		}
	}

}