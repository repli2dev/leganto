<?php

/**
 * Database logger for user actions
 * @author Jan Drabek
 */

namespace Leganto;

use DibiConnection,
    Nette\DI\IContainer,
    Nette\Diagnostics\Debugger,
    Exception,
    Nette\DateTime;

final class Logger {

	/** @var DibiConnection */
	private $connection;

	/** @var IContainer */
	private $container;

	public function __construct(IContainer $container) {
		$this->connection = $container->getService("database");
		$this->container = $container;
	}

	/**
	 * Log text into database along with id_user, url and time
	 * @param string $text text to be logged
	 * @param int $suser Only when need different one than currently logged user!
	 * @return NULL
	 */
	public function log($text = NULL, $user = NULL) {
		if (!empty($user)) {
			$values["id_user"] = $user;
		} else {
			$values["id_user"] = $this->container->user->getId();
		}
		if (empty($values["id_user"])) {
			return;
		}
		$uri = $this->container->httpRequest->getUrl();
		$values["url"] = $uri->path;
		if (!empty($uri->query)) {
			$values["url"] .= "?" . $uri->query;
		}
		$values["text"] = $text;
		$values["time"] = new DateTime;
		// Add tracking data
		$values["ip"] = $_SERVER["REMOTE_ADDR"];
		$values["browser"] = $_SERVER['HTTP_USER_AGENT'];
		try {
			return $this->connection->insert("user_log", $values)->execute();
		} catch (Exception $e) {
			Debugger::processException($e, TRUE);
		}
	}

}

?>
