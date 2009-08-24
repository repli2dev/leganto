<?php
/**
 * @author Jan Papousek
 */
interface IShelfSelector extends ISelector
{

	/**
	 * It returns shelfs by user
	 *
	 * @param UserEntity $user
	 * @return DibiDataSource
	 * @throws NullPointerException if the $user is empty
	 * @throws DibiDriverException if there is a problem to work with database
	 */
	public function findByUser(UserEntity $user);

}
