<?php
class EditionImageStorage extends EskymoObject implements IStorage
{

	public function getFile(IEntity $entity) {
		if ($entity->getState() != IEntity::STATE_PERSISTED) {
			throw new InvalidArgumentException("The entity has to be in state [persisted].");
		}
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

	/** @return File */
	public function getRandomFileByBook(BookEntity $book) {
		$files = $this->getFile($book);
		if (empty($file)) {
			return NULL;
		}
		return $files[rand(0,sizeof($files)-1)];
	}

	public function store(IEntity $edition, File $image) {
		if ($edition->getState() != IEntity::STATE_PERSISTED) {
			throw new InvalidArgumentException("The entity has to be persisted.");
		}
		$bookDirectory = new File($this->getDirectoryPath() . "/" . $edition->idBookTitle);
		if (!$bookDirectory->exists()) {
			$bookDirectory->mkdir();
		}
		$extension = ($image->getExtension() == NULL) ? "jpeg" : $image->getExtension();
		$destination = $bookDirectory->getPath() . "/" . $edition->getId() . "." . $extension;
		if (preg_match("/http/", $image->getPath())) {
			$ch = curl_init($image->getPath());
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$output = curl_exec($ch);
			curl_close($ch);
			$fp = fopen($destination, "w+");
			fwrite($fp, $output);
			fclose($fp);
		}
		else {
			$image->copy($destination);
		}
		
		$edition->image = $destination;
		$edition->persist();
		return new File($destination);
	}

	/** PRIVATE METHODS */

	private function getDirectoryPath() {
		return APP_DIR . "/../storage/books";
	}

}
