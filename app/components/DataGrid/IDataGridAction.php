<?php

/**
 * Defines method that must be implemented to allow a component act like a data grid action.
 *
 * @author     Roman Sklenář
 * @copyright  Copyright (c) 2009 Roman Sklenář
 * @package    Nette\Extras\DataGrid
 * @version    $Id: IDataGridAction.php 5 2009-05-02 21:38:31Z RSklenar@seznam.cz $
 */
interface IDataGridAction
{
	/**
	 * Gets action element template.
	 * @return Html
	 */
	function getHtml();

}