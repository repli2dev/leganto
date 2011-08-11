<?php

/**
 * Basic template to register helpers, filters, translator automatically
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\Templating;

use Nette\Templating\ITemplate,
    Nette\Latte\Engine,
    Nette\DI\IContainer;

final class Template {

	/** @var IContainer */
	private static $container;

	final private function __construct() {
		// Static class only
	}

	/**
	 * Set container (context), needed for access to services
	 * @param IContainer $container 
	 */
	public static function setContainer(IContainer $container) {
		self::$container = $container;
	}

	/**
	 * Adding basic helpers and system translator
	 * 
	 * @param ITemplate $template
	 * @return ITemplate
	 */
	public static function loadTemplate(ITemplate $template) {
		// register filters
		$template->registerFilter(new Engine);

		// register custom helpers
		// Better to use Nette date helper
		$template->registerHelper("date", Helpers::getHelper('date'));
		$template->registerHelper("time", Helpers::getHelper('time'));
		$template->registerHelper("texy", Helpers::getHelper('texy'));
		$template->registerHelper("texySafe", Helpers::getHelper('texySafe'));
		$template->registerHelper("thumbnail", Helpers::getHelper('thumbnail'));
		$template->registerHelper("userico", Helpers::getHelper('userIcon'));
		$template->registerHelper("bookcover", Helpers::getHelper('bookCover'));
		$template->registerHelper("language", Helpers::getHelper('language'));
		$template->registerHelper("hardTruncate", Helpers::getHelper('hardTruncate'));
		$template->registerHelper("rating", Helpers::getHelper('rating'));
		$template->registerHelper("achievementName", Helpers::getHelper('achievementName'));

		// give translator to templates
		$template->setTranslator(self::$container->getService("translator")->get());

		return $template;
	}

}