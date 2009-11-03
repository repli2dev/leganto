<?php
class BookCoverStorage
{

	const DIRECTORY = "";

	const DEFAULT_IMAGE = "";

	/**
	 * @var IBookImageFinder
	 */
	private $bookImageFinder;

	public function  __construct(IBookImageFinder $finder) {
		$this->bookImageFinder = $finder;
	}

	public function getImageByTitle(BookEntity $book, $load = FALSE) {
		$bookDirectory = new File(self::DIRECTORY . "/" . $book->bookNode);
		if (!$bookDirectory->exists()) {
			return NULL;
		}
		else {
			$files = $bookDirectory->listFiles(new FileNameFilter($book->getId() . "*"));
			if (empty($files)) {
				if ($load) {
					// TODO
					$coverImage = $this->bookImageFinder->getCoverImage($book);
					if (empty($coverImage)) {
						return new File(self::DEFAULT_IMAGE);
					}
					$this->storeImage($book, $image);
				}
				else {
					return new File(self::DEFAULT_IMAGE);
				}
			}
			else {
				return ExtraArray::firstValue($files);
			}
		}
	}

	public function getImages(BookEntity $book) {
		$bookDirectory = new File(self::DIRECTORY . "/" . $book->bookNode);
		if($bookDirectory->exists()) {
			return $bookDirectory->listFiles();
		}
		else {
			return array();
		}
	}

	public function storeImage(BookEntity $book, File $image) {
		$bookDirectory = new File(self::DIRECTORY . "/" . $book->bookNode);
		if (!$bookDirectory->exists()) {
			$bookDirectory->mkdir();
		}
		$image->copy($bookDirectory->getPath() . "/" . $book->getId() . "." . $image->getExtension());
	}
}
