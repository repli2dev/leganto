<?php

/**
 * Message entity
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\Message;

use Leganto\ORM\AEntity;

/**
 * @Id(translate=id_message)
 */
class Entity extends AEntity {

	/**
	 * @Translate(id_user_to)
	 */
	protected $idUserTo;

	/**
	 * @Translate(id_user_from)
	 */
	protected $idUserFrom;

	/**
	 * @Skip(Form)
	 */
	protected $inserted;
	protected $content;
	protected $read;

	/**
	 * @Translate(nickname_user_from)
	 */
	protected $nicknameUserFrom;

	/**
	 * @Translate(nickname_user_to)
	 */
	protected $nicknameUserTo;

	/**
	 * @Translate(deleted_by_owner)
	 */
	protected $deletedByOwner;

	/**
	 * @Translate(deleted_by_recipient)
	 */
	protected $deletedByRecipient;

}
