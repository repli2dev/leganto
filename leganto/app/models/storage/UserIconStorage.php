<?php

/**
 * Storage for users avatars
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */

namespace Leganto\Storage;

use Leganto\IO\FileNameFilter,
    Leganto\Tools\ExtraArray,
    InvalidArgumentException,
    Leganto\IO\File,
    Leganto\ORM\IEntity;

class UserIconStorage implements IStorage {

	public function getFile(IEntity $entity) {
		if ($entity->getState() != IEntity::STATE_PERSISTED) {
			throw new InvalidArgumentException("The entity has to be in state [persisted].");
		}
		if (get_class($entity) != "\Leganto\DB\User\Entity") {
			throw new InvalidArgumentException("The entity has to be user.");
		}

		return $this->getFileById($entity->getId());
	}

	/**
	 * Return file by userId
	 * @param int $userId
	 * @return File 
	 * @throws InvalidArgumentException if user id is empty
	 */
	public function getFileById($userId) {
		if (empty($userId)) {
			throw new InvalidArgumentException("userId");
		}
		$storage = new File($this->getDirectoryPath());
		$icons = $storage->listFiles(new FileNameFilter($userId . ".*"));
		return ExtraArray::firstValue($icons);
	}

	public function store(IEntity $user, File $image) {
		if ($user->getState() != IEntity::STATE_PERSISTED) {
			throw new InvalidArgumentException("The entity has to be persisted.");
		}
		// Delete old file
		$old = $this->getFile($user);
		if (!empty($old)) {
			$old->delete();
		}

		// Store new file
		$extension = ($image->getExtension() == NULL) ? "jpeg" : $image->getExtension();
		$destination = $this->getDirectoryPath() . "/" . $user->getId() . "." . $extension;
		$image->copy($destination);

		return new File($destination);
	}

	private function getDirectoryPath() {
		return WWW_DIR . "/storage/users";
	}

}
