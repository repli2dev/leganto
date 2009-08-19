<?php
/**
 * @Tag Jan Papousek
 */
class TagFactory extends AEntityFactory
{

	/** @return TagEntity */
	public function createEmpty() {
		return new TagEntity();
	}

}
