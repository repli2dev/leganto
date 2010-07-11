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
 */
final class LegantoTemplate
{

	final private function  __construct() {}

	/**
	 * Adding basic helpers and system translator
	 * @param ITemplate $template
	 * @return ITemplate
	 */
	public static function loadTemplate(ITemplate $template){
		// register filters
		$template->registerFilter('CurlyBracketsFilter::invoke');

		// register custom helpers
		$template->registerHelper("date", Helpers::getHelper('date'));
		$template->registerHelper("time", Helpers::getHelper('time'));
		$template->registerHelper("texy", Helpers::getHelper('texy'));
		$template->registerHelper("thumbnail", Helpers::getHelper('thumbnail'));
                $template->registerHelper("userico", Helpers::getHelper('userIcon'));

		$template->setTranslator(System::translator());

		return $template;
	}

}