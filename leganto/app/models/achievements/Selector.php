<?php
namespace Leganto\DB\Achievement;
use Leganto\ORM\Workers\ISelector,
	Leganto\ORM\Exceptions,
	Leganto\DB\Factory,
	dibi;

class Selector implements ISelector {

	public function find($id) {
		throw new NotSupportedException();
	}

	public function findAll() {
		return dibi::dataSource("SELECT * FROM [tmp_achievement]");
	}

	public function findByUser(UserEntity $user) {
		return Factory::achievements()->fetchAndCreate(dibi::dataSource("SELECT * FROM [tmp_achievement] WHERE [id_user] = %i", $user->getId()));
	}

	public function findByUsers(array $users, $entities = TRUE) {
		if (empty($users)) {
			throw new \InvalidArgumentException();
		}
		else {
			if ($entities) {
				$userIds = array();
				foreach($users AS $user) {
					$userIds[] = $user->getId();
				}
			}
			else {
				$userIds = $users;
			}
			$source = dibi::dataSource("SELECT * FROM [tmp_achievement] WHERE [id_user] IN %l", $userIds);
			$result = array();
			while($entity = Factory::achievements()->fetchAndCreate($source)) {
				$result[$entity->idUser] = $entity;
			}
			return $result;
		}
	}

}
