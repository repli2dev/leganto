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
namespace Leganto\DB\Discussion;
use Leganto\ORM\AEntity;

/**
 * @Id(translate=id_discussion)
 */
class Entity extends AEntity {

	/**
	 * ID of the entity which is discussed
	 *
	 * @Translate(id_discussed)
	 */
	protected $discussed;
	/**
	 * ID of the discussion type
	 *
	 * @Translate(id_discussable)
	 */
	protected $discussionType;
	protected $inserted;
	/**
	 * @Skip(Save)
	 * @Translate(last_post_inserted)
	 */
	protected $lastPostInserted;
	/**
	 * @Skip(Save)
	 */
	protected $name;
	/**
	 * @Skip(Save)
	 * @Translate(number_of_posts)
	 */
	protected $numberOfPosts;
	/**
	 * @Skip(Save)
	 */
	protected $subname;
}
