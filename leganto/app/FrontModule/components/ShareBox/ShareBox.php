<?php

/**
 * Links for sharing current page on socialnetworks
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace FrontModule\Components;

use Nette\Environment,
    Leganto\DB\Factory;

class ShareBox extends BaseComponent {

	public function beforeRender() {
		$url = $this->getContext()->httpRequest->getUrl()->absoluteUri;
		$this->getTemplate()->url = urlencode($url);
		$bookId = $this->getPresenter()->getParam("book");
		$book = Factory::book()->getSelector()->find($bookId);
		$title = $this->translate("Book") . ": " . $book->title;
		$this->getTemplate()->title = urlencode($title);
	}

}