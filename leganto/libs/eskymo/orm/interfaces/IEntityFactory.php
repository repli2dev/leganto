<?php

/**
 * Interface of factory for entites
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (repli2dev@gmail.com)
 * @license		http://www.opensource.org/licenses/bsd-license.php
 */

namespace Leganto\ORM;

use IDataSource,
    Leganto\ORM\IInsertable,
    Leganto\ORM\IUpdateable,
    Leganto\ORM\ISelectable,
    Leganto\ORM\IDeletable,
    Leganto\ORM\IEntity,
    DibiConnection;

interface IEntityFactory extends IInsertable, IUpdateable, ISelectable, IDeletable {

	/**
	 * @return IEntity
	 */
	function createEmpty();

	/**
	 * @return IEntity
	 */
	function fetchAndCreate(IDataSource $source);

	/**
	 * 
	 * @return array 
	 */
	function fetchAndCreateAll(IDataSource $source);
}
