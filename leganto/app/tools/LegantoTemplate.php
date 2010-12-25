<?php

/**
 * Basic template to register helpers, filters, translator automatically
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
final class LegantoTemplate {

	final private function __construct() {

	}

	/**
	 * Adding basic helpers and system translator
	 * @param ITemplate $template
	 * @return ITemplate
	 */
	public static function loadTemplate(ITemplate $template) {
		// register filters
		$template->registerFilter(new LatteFilter);

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

		// give translator to templates
		$template->setTranslator(System::translator());

		return $template;
	}

}