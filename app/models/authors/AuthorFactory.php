<?php
/**
 * @author Jan Papousek, Jan Drabek
 */
class AuthorFactory extends AEntityFactory
{
	/** @return AuthorEntity */
	public function createEmpty() {
		return new AuthorEntity();
	}

}
