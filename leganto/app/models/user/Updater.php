<?php

/**
 * User updater
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\User;

use Leganto\ORM\Workers\IUpdater,
    Leganto\DB\Factory,
    Leganto\Tools\ExtraString,
    Leganto\ORM\SimpleTableModel,
    Leganto\ORM\Workers\SimpleUpdater,
    Leganto\ORM\Workers\AWorker,
    Leganto\ORM\IEntity,
    Nette\InvalidStateException,
    Nette\DateTime;

class Updater extends AWorker implements IUpdater {
	const ERROR_OLD_HASH = 100;

	const ERROR_WRONG_HASH = 101;

	/* PUBLIC METHODS */

	public function update(IEntity $entity) {
		return SimpleUpdater::createUpdater("user", $this->connection)->update($entity);
	}

	/**
	 * Create new pass and store it
	 * @param Entity $entity
	 * @return string
	 */
	public function generateHashForNewPassword(Entity $entity) {
		$hash = ExtraString::random(30);
		$this->connection->update("user", array(
			    "new_pass_key" => $hash,
			    "new_pass_time" => new DateTime()
				)
			)
			->where("id_user = %i", $entity->id)
			->execute();
		return $hash;
	}

	/**
	 * Function for confirming and changing users password
	 * @param Entity $entity
	 * @param string $hash pass code
	 * @return string new password
	 * @throws InvalidStateException if hash is wrong or too old
	 */
	public function confirmNewPassword(Entity $entity, $hash) {
		$row = SimpleTableModel::createTableModel("user", $this->connection)->find($entity->getId());
		if (!empty($row->new_pass_time) && strtotime($row->new_pass_time) > (time() - 24 * 60 * 60)) {
			if ($row->new_pass_key == $hash) {
				$newPassword = ExtraString::random(10);
				$this->connection->update("user", array(
					    "new_pass_key" => "",
					    "new_pass_time" => "NULL",
					    "password" => Authenticator::passwordHash($newPassword)
						)
					)
					->where("id_user = %i", $entity->id)
					->execute();
				return $newPassword;
			} else {
				throw new InvalidStateException("The hash is wrong.", self::ERROR_WRONG_HASH);
			}
		} else {
			throw new InvalidStateException("The hash is too old.", self::ERROR_OLD_HASH);
		}
	}

	/**
	 * Remove pass code from from user given by user entity (e.g. after successful login)
	 * @param Entity $entity
	 */
	public function removePassCode(Entity $entity) {
		if ($entity->new_pass_key) {
			$this->connection->update("user", array(
				    'new_pass_key' => '',
				    'new_pass_time' => 'NULL'
					)
				)
				->where("id_user = %i", $entity->id)
				->execute();
		}
	}

	/**
	 * Remove invalid pass codes (those which expired)
	 */
	public function removeOldPassCodes() {
		$this->connection->update("user", array(
			    'new_pass_key' => '',
			    'new_pass_time' => 'NULL'
				)
			)
			->where("UNIX_TIMESTAMP(new_pass_time) < %i", time() - 24 * 60 * 60)
			->execute();
	}

	/**
	 * Toogle follow/unfollow of given user and current logged user
	 * @param int $user id of user
	 * @param \Leganto\DB\User\Entity $by
	 * @return Boolean
	 */
	public function toogleFollow($user,$by) {
		if (Factory::user()->getSelector()->isFollowedBy($user, $by)) {
			$this->connection->delete("following")
				->where("id_user = %i", $by->getId())
				->where("id_user_followed = %i", $user)
				->execute();
			return FALSE;
		} else {
			$this->connection->insert("following", array(
			    'id_user' => $by->getId(),
			    'id_user_followed' => $user
				)
			)->execute();
			return TRUE;
		}
	}

}

?>
