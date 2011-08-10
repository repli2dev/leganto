<?php

/**
 * Cron BasePresenter
 * @author Jan Papousek
 * @author Jan Drabek
 */

namespace CronModule;

use Nette\Diagnostics\Debugger;

class BasePresenter extends \BasePresenter {
	
	/** @var DibiConnection */
	protected $connection;

	protected function startUp() {
		parent::startup();
		$givenKey = $this->getParam("key");
		$expectedKey = $this->getContext()->params["cron"]["key"];
		if (empty($givenKey) || $givenKey != $expectedKey) {
			$this->unauthorized();
		}
		
		$this->connection = $this->getContext()->getService("database");

		if (!ini_get('safe_mode')) {
			set_time_limit(300);
			$this->flashMessage("Time limit has been set to 300s.", "success");
			ini_set('memory_limit', '128M');
			$this->flashMessage("Time limit has been set to 128M.", "success");
		}
	}

	protected function beforeRender() {
		Debugger::timer("cron");
	}

	protected function createTemplate() {
		$template = parent::createTemplate();
		$template->setFile(dirname(__FILE__) . "/../templates/default.latte");
		return $template;
	}

	protected final function unauthorized() {
		$this->flashMessage("UNAUTHORIZED", "error");
		$this->getTemplate()->render();
		$this->terminate();
	}

}
