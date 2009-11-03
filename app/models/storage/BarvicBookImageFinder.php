<?php
class BarvicBookImageFinder implements IBookImageFinder
{

	const URL_PATTERN = "http://www.knihkupectvi-bn.cz/exec/search.aspx?exps=<--QUERY-->&SearchType=Fulltext";

	/**
	 * It tries to find an cover image of the book
	 *
	 * @param BookEntity $book The specified book
	 * @return File or NULL if there is no image avaiable
	 */
	public function getImage(BookEntity $book) {
		if (($imageURL = $this->findImageURL($book)) == NULL) {
			return NULL;
		}
		else {
			return new File($imageURL);
		}
	}

	// PRIVATE METHODS

	/**
	 * It tries to find image URL
	 *
	 * @param BookEntity $book
	 * @return string|NULL
	 */
	private function findImageURL(BookEntity $book) {
		$pageContent = $this->getURLContent($this->getSearchURL($book));
		preg_match("/http:\/\/www\.knihkupectvi-bn\.cz\/fotocache\/bigorig[^>\"]+\.jpg/", $pageContent, $matches);
		if (empty($matches)) {
			return NULL;
		}
		else {
			return $matches[0];
		}

	}

	/**
	 * It returns URL where the results of book searching are displayed
	 *
	 * @param BookEntity $book
	 * @return string
	 */
	private function getSearchURL(BookEntity $book) {
		if (!$book->isReadyToUpdate()) {
			throw new InvalidStateException("The entity has to be ready to be updated.");
		}
		$query = "";
		$query .= $book->title;
		if (!empty($book->subtitle)) {
			$query .= " " . $book->subtitle;
		}
		$authors = Leganto::authors()->fetchAndCreateAll(
			Leganto::authors()->getSelector()->findAllByBook($book)
		);
		foreach($authors AS $author) {
			if ($author->type == AuthorEntity::GROUP) {
				$query .= " " . $author->groupname;
			}
			else {
				$query .= " " . $author->firstname . " " . $author->lastname;
			}
		}
		$query = urlencode($query);
		return strtr(self::URL_PATTERN, array("<--QUERY-->" => $query));
	}

	/**
	 * It returns a content which is placed on the specified address.
	 *
	 * @param string $url
	 * @return string
	 */
	private function getURLContent($url) {
		$ch = curl_init($url);
		// The data should be returned (not printed)
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$output = curl_exec($ch);
		curl_close($ch);
		// HACK
		if (preg_match("/Object moved to/", $output) == 0) {
			return $output;
		}
		else {
			preg_match("/http.+\d+\//", $output, $matches);
			return $this->getURLContent($matches[0]);
		}
	}

}
