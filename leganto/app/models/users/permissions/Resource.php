<?php
/**
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */

class Resource implements IResource {
	const AUTHOR = "author";

	const BOOK = "book";

	const EDITION = "edition";

	const POST = "post";

	const OPINION = "opinion";

	const SHELF = "shelf";

	const TAG = "tag";

	const TOPIC = "topic";

	const USER = "user";

	private $ownerId;
	private $resource;

	private final function __construct($resource, $ownerId = NULL) {
		$this->ownerId = $ownerId;
		$this->resource = $resource;
	}

	public static function create(IEntity $entity) {
		$resource = String::lower(strtr(get_class($entity), array("Entity" => "")));
		switch ($resource) {
			case "opinion":
				return new Resource($resource, $entity->userId);
				break;
			case "post":
				return new Resource($resource, $entity->user);
				break;
			case "shelf":
				return new Resource($resource, $entity->user);
				break;
			case "user":
				return new Resource($resource, $entity->getId());
				break;
			default:
				throw new NotSupportedException("The resource [$resource] is not supported.");
		}
	}

	public function getOwnerId() {
		return $this->ownerId;
	}

	public function getResourceId() {
		return $this->resource;
	}

}

