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

/**
 * @Id(translate=id_message)
 */
class MessageEntity extends AEntity {

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
}
