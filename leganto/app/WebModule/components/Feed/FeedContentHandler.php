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

class FeedContentHandler {
	/**
	 * Decide what template use, prepare content to be inserted
	 * @param FeedItemEntity $item to show
	 * @return Template
	 */
	public function handle(FeedItemEntity $item) {
		if(file_exists(__DIR__."/events/".$item->type.".phtml")) {
			$template = LegantoTemplate::loadTemplate(new Template(__DIR__."/events/".$item->type.".phtml"));
			// FIXME: proc tohle neni automaticky?
			$template->registerHelper('stripTags', 'strip_tags');
		} else {
			return;
		}
		$this->fillTemplate($template,$item);
		return $template;
	}
	private function fillTemplate($template, $item) {
		$template->userId = $item->userId;
		$template->userNick = $item->userNick;
		$template->type = $item->type;
		$template->content = explode("#$#",$item->content);
		$template->inserted = $item->inserted;
	}

}

?>
