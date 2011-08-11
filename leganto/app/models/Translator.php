<?php

/*
 * Translator wrapper to use as service and cache
 * @author Jan Drabek
 * 
 */

namespace Leganto\Localization;

use Nette\Localization\ITranslator,
    Nette\DI\IContainer,
    GettextTranslator,
    Nette\InvalidStateException,
    Nette\Caching\Storages\FileStorage,
    Nette\Caching\IStorage,
    Nette\Caching\Cache;

final class Translator {

	/** @var ITranslator */
	private $translator;

	/** @var IContainer */
	private $container;
	
	/** @var Cache */
	private $cache;

	public function __construct(IContainer $container) {
		$this->container = $container;
	}

	/**
	 * Lazy init of translator
	 * @return ITranslator
	 */
	public function get() {
		if (empty($this->translator)) {
			// Try to find translator in cache
			$cache = $this->getCache();
			if ($cache->offsetExists("translator")) {
				return $cache->offsetGet("translator");
			} else {
				// If unsuccessful then create new
				$lang = $this->getLang();
				$this->translator = new GettextTranslator($this->getAppDir() . '/locale/' . $lang . '/messages.mo');
				$cache->save("translator", $this->translator, array(
				    'expire' => time() + 60 * 60 * 6, // expire in 6 hours
				));
			}
		}
		return $this->translator;
	}

	/** @return string */
	public function translate($message, $count = 1) {
		$args = func_get_args();
		return call_user_func_array(array($this->get(), 'translate'), $args);
	}

	private function getCache() {
		if(empty($this->cache)) {
			$this->cache = new Cache($this->container->cacheStorage,"translator");
		}
		
		return $this->cache;
	}

	private function getAppDir() {
		return $this->container->params["appDir"];
	}

	/** @return string */
	private function getLang() {
		if (!$this->container->hasService("environment")) {
			throw new InvalidStateException("Service environment needed");
		}
		$domain = $this->container->getService("environment")->domain();
		if (empty($domain)) {
			$lang = "en_US";
		} else {
			$lang = $domain->locale;
		}
		return $lang;
	}

}

?>
