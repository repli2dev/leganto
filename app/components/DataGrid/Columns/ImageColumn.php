<?php

require_once dirname(__FILE__) . '/TextColumn.php';



/**
 * Representation of image data grid column.
 *
 * @author     Roman Sklenář
 * @copyright  Copyright (c) 2009 Roman Sklenář
 * @example    http://nettephp.com/extras/datagrid
 * @package    Nette\Extras\DataGrid
 * @version    $Id: ImageColumn.php 5 2009-05-02 21:38:31Z RSklenar@seznam.cz $
 */
class ImageColumn extends TextColumn
{
	/**
	 * Checkbox column constructor.
	 * @param  string  column's textual caption
	 * @return void
	 */
	public function __construct($caption = NULL)
	{
		throw new NotImplementedException("Class was not implemented yet.");
		parent::__construct($caption);
	}
}