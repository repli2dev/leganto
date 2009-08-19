<?php
/**
 * @author Jan Papousek
 */
class UserFactory extends AEntityFactory
{

	/** @return UserEntity */
	public function createEmpty() {
		return new UserEntity();
	}

}
