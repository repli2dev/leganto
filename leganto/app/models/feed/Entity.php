<?php
/**
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
namespace Leganto\DB\Feed;
use Leganto\ORM\AEntity;

/** @Id(translate=id_item) */
class Entity extends AEntity {
	const TYPE_NEW_OPINION = "new_opinion";
	const TYPE_UPDATED_OPINION = "updated_opinion";
	const TYPE_NEW_DISCUSSION = "new_discussion";
	const TYPE_NEW_POST = "new_post";
	const TYPE_NEW_FOLLOWER = "new_follower"; 
	const TYPE_SHELVED = "shelved";
	const TYPE_DESHELVED = "deshelved";
	const TYPE_NEW_BOOK = "new_book";
	const TYPE_NEW_USER = "new_user";

	/** @Translate(id_user) */
	protected $userId;
	/** @Translate(user_nick) */
	protected $userNick;
	protected $type;
	protected $content;
	protected $inserted;
}