<?php
/**
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
namespace FrontModule\Components;
use Nette\Environment,
	Leganto\DB\Factory,
	Leganto\System;
class ShareBox extends BaseComponent {

	public function beforeRender() {
		$url = Environment::getHttpRequest()->uri->absoluteUri;
		$this->getTemplate()->url = urlencode($url);
		$bookId = $this->getPresenter()->getParam("book");
		$book = Factory::books()->getSelector()->find($bookId);
		$title = System::translate("Book") . ": " . $book->title;
		$this->getTemplate()->title = urlencode($title);
	}

}