<?php

/**
 * Find book informations on google books
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\External;

use InvalidArgumentException,
    Nette\IOException,
    Nette\InvalidStateException,
    Leganto\ORM\IEntity,
    Leganto\DB\Factory;

class GoogleBooksBookFinder extends AFinder {
	const XML_URL = "http://books.google.com/books/feeds/volumes?q=<--QUERY-->&lr=<--LANG-->";

	/**
	 * Set query language
	 * @param string $lang google code of language
	 * @throws InvalidArgumentException if language is empty
	 */
	public function __construct($language) {
		if (empty($language)) {
			throw new InvalidArgumentException("Empty language.");
		}
		$this->setUrlParam("LANG", $language);
	}

	/**
	 * It tries to find the book on google books
	 *
	 * @param \Leganto\DB\Book\Entity $book The specified book
	 * @return array or NULL
	 */
	public function get($entity) {
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
		$data = @simplexml_load_string($pageContent);
		$params = $this->getUrlParams();
		if ($data === false || !isSet($data->entry)) {
			throw new IOException("No results for query: " . urldecode($params['<--QUERY-->']));
		}
		// Get Google Book ID of first item
		$entry = $data->entry->children('http://purl.org/dc/terms'); // Switch the namespaces
		$gid = $entry->identifier[0];

		if (empty($gid)) {
			throw new IOException("Unknown error.");
		} else {
			// Ask editionFinder to do the job
			$editionFinder = new GoogleBooksEditionFinder($params['<--LANG-->']);

			return $editionFinder->get($gid);
		}
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

		// Add title (and subtitle) to query
		$query .= 'intitle:' . $entity->title . ($entity->subtitle != null ? ' ' . $entity->subtitle : '');

		// Add author to query
		$authors = Factory::author()->fetchAndCreateAll(
			Factory::author()->getSelector()->findAllByBook($entity)
		);
		foreach ($authors AS $author) {
			if ($author->type == AuthorEntity::GROUP) {
				$query .= ' inauthor:' . $author->groupname . '';
			} else {
				$query .= ' inauthor:' . $author->lastname;
			}
		}
		return urlencode($query);
	}

}