<?php
class EditionImageFinder extends AFinder
{

	const URL = "http://obalkyknih.cz/view?isbn=<--ISBN-->";

	public function get($edition) {
		if ($edition->getState() != IEntity::STATE_PERSISTED) {
			throw new InvalidArgumentException("The entity has to be persisted.");
		}
		if ($edition->isbn10 != NULL) {
			$this->setUrlParam("isbn", $edition->isbn10);
		}
		else if($edition->isbn13 != NULL) {
			$this->setUrlParam("isbn", $edition->isbn13);
		}
		else {
			throw new InvalidArgumentException("The entity has no ISBN.");
		}
		$content = $this->getUrlContent($this->getParsedUrl());
		preg_match("/http:\/\/www\.obalkyknih\.cz\/file\/cover\/\d+\/medium/", $content, $matches);
		return $matches;
	}

	/* PROTECTED METHODS */
	protected function getUrl() {
		return self::URL;
	}

}