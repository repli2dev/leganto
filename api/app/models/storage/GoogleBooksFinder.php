<?php
// FIXME: nefunguje treba na Krakatitu
class GoogleBooksFinder implements IBookISBNFinder
{

	// TODO: dynamicky volit jazyk
	const URL_PATTERN = "http://books.google.com/books/feeds/volumes?q=%22<--QUERY-->%22&max-results=1&lr=cs";

	/**
	 * It tries to find the IBSN of the book
	 *
	 * @param BookEntity $book The specified book
	 * @return int or NULL
	 */
	public function getISBN(BookEntity $book) {
		if (($bookISBN = $this->findISBN($book)) == NULL) {
			return NULL;
		}
		else {
			return strtr($bookISBN,array("ISBN:" => ""));
		}
	}

	// PRIVATE METHODS

	/**
	 * It tries to find ISBN of the book in the feed
	 *
	 * @param BookEntity $book
	 * @return string|NULL
	 */
	private function findISBN(BookEntity $book) {
		$pageContent = $this->getURLContent($this->getSearchURL($book));
		preg_match("/ISBN:[0-9\-]*/u", $pageContent, $matches);
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
		// TODO: bylo by dobre pridat do vyhledavani i autora
		/*$authors = Leganto::authors()->fetchAndCreateAll(
			Leganto::authors()->getSelector()->findAllByBook($book)
		);
		foreach($authors AS $author) {
			if ($author->type == AuthorEntity::GROUP) {
				$query .= " " . $author->groupname;
			}
			else {
				$query .= " " . $author->firstname . " " . $author->lastname;
			}
		}*/
		$query = urlencode($query);
		return strtr(self::URL_PATTERN, array("<--QUERY-->" => $query));
	}

	// TODO: Osamostatnit
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
		// HACK presmerovani
		if (preg_match("/Object moved to/", $output) == 0) {
			return $output;
		}
		else {
			preg_match("/http.+\d+\//", $output, $matches);
			return $this->getURLContent($matches[0]);
		}
	}

}
