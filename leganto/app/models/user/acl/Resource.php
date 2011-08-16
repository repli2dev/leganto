<?php

/**
 * (Protected) resource
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\ACL;

use Nette\Security\IResource,
    Leganto\ORM\IEntity,
    Leganto\Templating\Helpers,
    Nette\Environment;

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

	const MESSAGE = "message";

	const HELP = "help";

	private $ownerId;
	private $resource;

	private final function __construct($resource, $ownerId = NULL) {
		$this->ownerId = $ownerId;
		$this->resource = $resource;
	}

	public static function create(IEntity $entity) {
		$resource = get_class($entity);
		switch ($resource) {
			case "Leganto\DB\Opinion\Entity":
				$resource = self::OPINION;
				return new Resource($resource, $entity->userId);
				break;
			case "Leganto\DB\Post\Entity":
				$resource = self::POST;
				return new Resource($resource, $entity->user);
				break;
			case "Leganto\DB\Shelf\Entity":
				$resource = self::SHELF;
				return new Resource($resource, $entity->user);
				break;
			case "Leganto\DB\User\Entity":
				$resource = self::USER;
				return new Resource($resource, $entity->getId());
				break;
			case "Leganto\DB\Message\Entity":
				$resource = self::MESSAGE;
				if (Environment::getUser()->getId() == $entity->idUserFrom) {
					$id = $entity->idUserFrom;
				} else {
					$id = $entity->idUserTo;
				}
				return new Resource($resource, $id);
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

