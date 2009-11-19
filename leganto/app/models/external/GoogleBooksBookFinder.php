<?php
/**
 * The source file is subject to the license located on web
 * "http://code.google.com/p/preader/".
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (repli2dev@gmail.com)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 */

/**
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$Id$
 */

 class GoogleBooksBookFinder extends AFinder {

	const AUTHOR		= "author";

	const FORMAT		= "format";

	const IDENTIFIER	= "isbn";

	const PAGES			= "pages";

	const PUBLISHED		= "publishDate";

	const TITLE			= "title";

	const XML_URL		= "http://books.google.com/books/feeds/volumes?q=<--QUERY-->&lr=<--LANG-->";

	/**
	 * Set query language
	 * @param string $lang google code of language
	 */
	public function  __construct($language) {
		if (empty($language)) {
			throw new NullPointerException("language");
		}
		$this->setUrlParam("LANG", $language);
	}

	/**
	 * It tries to find the book on google books
	 *
	 * @param BookEntity $book The specified book
	 * @return array or NULL
	 */
	public function get(IEntity $entity) {
		$this->setUrlParam("QUERY", $this->makeQuery($entity));
		return $this->fetchAndParse();
	}

	/* PROTECTED METHODS */

	protected function getUrl() {
		return self::XML_URL;
	}

	/* PRIVATE METHODS */

	private function fetchAndParse() {
		$pageContent = $this->getUrlContent($this->getParsedUrl());
		// Parsing...
		$data = simplexml_load_string($pageContent);
		$i = 0;
		$output = array();
		foreach($data->entry as $entry){
			$output[$i] = array();
			$entry = $entry->children('http://purl.org/dc/terms'); // Switch the namespaces
			// FIXME: co prekladatele?!
			foreach($entry->creator as $creator){
				if (!isset($output[$i]['author'])) {
					$output[$i]['author'] = array();
				}
				$output[$i]['author'][] = (string) $creator;
			}
			$output[$i][self::PUBLISHED] = (int) $entry->date;
			$output[$i][self::FORMAT] = (string) $entry->format[1];
			preg_match("/\d+/", (string) $entry->format[0], $matches);
			$output[$i][self::PAGES] = ExtraArray::firstValue($matches);
			$output[$i][self::TITLE] = (string) $entry->title;
			foreach($entry->identifier as $identifier){
				$output[$i][self::IDENTIFIER][] = (string) $identifier;
			}
			$i++;

		}
		return $output;
	}

	/**
	 * It returns URL where the results of book searching are displayed
	 *
	 * @param Entity $etity
	 * @return string
	 */
	private function makeQuery(IEntity $entity) {
		if ($entity->getState() != IEntity::STATE_PERSISTED) {
			throw new InvalidStateException("The entity has to be in state [PERSISTED].");
		}
		$query = "";

		// Add title to query
		$query .= 'intitle:"'.$entity->title.'"';

		// Add author to query
		$authors = Leganto::authors()->fetchAndCreateAll(
			Leganto::authors()->getSelector()->findAllByBook($entity)
		);
		foreach($authors AS $author) {
			if ($author->type == AuthorEntity::GROUP) {
				$query .= ' inauthor:"' . $author->groupname.'"';
			}
			else {
				$query .= ' inauthor:"'. $author->firstname . ' ' . $author->lastname.'"';
			}
		}
		return urlencode($query);
	}
 }