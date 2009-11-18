<?php
class EditionImageStorage extends EskymoObject implements IStorage
{

	public function getFile(IEntity $entity) {
		switch(get_class($entity)) {
			case "BookEntity":
				$file = new File($this->getDirectoryPath() . "/" . $entity->getId());
				if (!$file->exists()) {
					return NULL;
				}
				break;
			case "EditionEntity":
				$dir = new File($this->getDirectoryPath() . "/" . $entity->idBook);
				$files = $dir->listFiles(new FileNameFilter($entity->getId() . ".*"));
				if (empty($files)) {
					return NULL;
				}
				$file = ExtraArray::firstValue($files);
				break;
			default:
				throw new InvalidArgumentException("The entity has to be book or edition.");
		}
		return $file;
	}

	public function store(IEntity $edition, File $image) {
		if ($edition->getState() != IEntity::STATE_PERSISTED) {
			throw new InvalidArgumentException("The entity has to be persisted.");
		}
		$bookDirectory = new File($this->getDirectoryPath() . "/" . $edition->idBook);
		if (!$bookDirectory->exists()) {
			$bookDirectory->mkdir();
		}
		$destination = $bookDirectory->getPath() . "/" . $edition->getId() . "." . $image->getExtension();
		$image->copy($destination);
		$edition->image = $destination;
		$edition->persist();
	}

	/** PRIVATE METHODS */

	private function getDirectoryPath() {
		return APP_DIR . "/../storage";
	}

}
