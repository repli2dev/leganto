<?php

/**
 * System class maintain domains <-> languages
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\Localization;

use
    Leganto\ORM\SimpleEntityFactory,
    Leganto\DB\Factory,
    Nette\DI\IContainer;

final class Environment {

	/** @var \Leganto\DB\Domain\Entity */
	private $domain;

	/** @var \Leganto\DB\Language\Entity */
	private $language;
	
	/** @var IContainer */
	private $container;

	public function __construct(IContainer $container) {
		$this->container = $container;
	}

	/** @return IEntity */
	public function domain() {
		if (!isset($this->domain)) {
			$source = SimpleEntityFactory::createEntityFactory("domain", $this->container->getService("database"))
				->getSelector()
				->findAll()
				->where("[uri] = %s", self::getHTTPHost());
			$this->domain = SimpleEntityFactory::createEntityFactory("domain", $this->container->getService("database"))->fetchAndCreate($source);
		}
		return $this->domain;
	}

	private static function getHTTPHost() {
		return ltrim(@$_SERVER['HTTP_HOST'], "www.");
	}

	/** @return IEntity */
	public function language() {
		if (!isset($this->language)) {
			$this->language = SimpleEntityFactory::createEntityFactory("language", $this->container->getService("database"))
				->getSelector()
				->find($this->domain()->idLanguage);
		}
		return $this->language;
	}
}