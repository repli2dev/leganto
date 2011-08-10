<?php

/**
 * Text captcha entity
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\Captcha;

use Leganto\ORM\AEntity;

/**
 * @Id(translate=id_captcha)
 */
class Entity extends AEntity {

	protected $question;
	protected $answer;

}
