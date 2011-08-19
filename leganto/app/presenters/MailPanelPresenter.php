<?php

/**
 * Service presenter for Mail Panel
 * @author Jan Drábek
 * @version 1.0
 * @copyright GNU-GPLv3
 */



use Nette\Environment,
    Nette\Diagnostics\Debugger,
    Nette\Templating\FileTemplate,
    Nette\Latte\Engine;

class MailPanelPresenter extends \Nette\Application\UI\Presenter {

	/** @var Session */
	private $container;

	public function __construct() {
		$this->container = Nette\Environment::getSession("session-dummy");
		if (!Debugger::isEnabled()) {
			$this->terminate();
		}
	}

	public function actionDeleteAll() {
		unset($this->container->queue);
		$this->returnWay();
	}

	public function actionDelete($id) {
		foreach ($this->container->queue as $key => $row) {
			if ($key == $id)
				$this->container->queue->offsetUnset($key);
		}
		$this->returnWay();
	}

	public function actionShowMore($count) {
		if (!is_numeric($count) || $count <= 0) {
			return;
		}
		$this->container->count = $this->container->count + $count;
		$this->returnWay();
	}

	public function actionShowLess($count) {
		if (!is_numeric($count) || $count <= 0) {
			return;
		}
		$this->container->count = $this->container->count - $count;
		$this->returnWay();
	}

	public function actionDetail($id) {
		// Disable showing debugger etc
		Debugger::enable(Debugger::PRODUCTION);
		// Template and rendering
		$template = new FileTemplate(__DIR__ . "/MailPanelDetail.latte");
		$template->registerFilter(new Engine);
		$template->registerHelper("nl2br", "nl2br");
		$template->mail = $this->container->queue->offsetGet($id);
		$template->render();
		$this->terminate();
	}

	private function returnWay() {
		$this->redirectUrl(Environment::getHttpRequest()->getReferer());
		$this->terminate();
	}

}