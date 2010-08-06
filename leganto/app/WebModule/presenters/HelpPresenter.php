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
class Web_HelpPresenter extends Web_BasePresenter {
	public function renderDefault() {
		$this->setPageTitle(System::translate("Help"));
		$this->getTemplate()->data = Leganto::supportCategory()->getSelector()->findAllSortedByWeight();
	}
	
	
}
