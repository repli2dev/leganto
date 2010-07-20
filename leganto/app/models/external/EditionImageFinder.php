<?php
class EditionImageFinder extends AFinder
{

	const URL = "http://obalkyknih.cz/view?isbn=<--ISBN-->";

	public function get($edition) {
		if ($edition->getState() != IEntity::STATE_PERSISTED) {
			throw new InvalidArgumentException("The entity has to be persisted.");
		}
		$this->setUrlParam("isbn", $edition->isbn9);
		$content = $this->getUrlContent($this->getParsedUrl());
		preg_match("/http:\/\/www\.obalkyknih\.cz\/file\/cover\/\d+\/medium/", $content, $matches);
		return $matches;
	}

	/* PROTECTED METHODS */
	protected function getUrl() {
		return self::URL;
	}

}