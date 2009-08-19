<?php

/**
 * @author Jan Papousek, Jan Drabek
 */
class BookFactory extends AEntityFactory
{

	/** @return BookEntity */
	public function createEmpty() {
		return new BookEntity();
	}

}
