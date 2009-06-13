<?php

/**
 * Defines method that must implement data grid rendered.
 *
 * @author     Roman Sklenář
 * @copyright  Copyright (c) 2009 Roman Sklenář
 * @package    Nette\Extras\DataGrid
 * @version    $Id: IDataGridRenderer.php 5 2009-05-02 21:38:31Z RSklenar@seznam.cz $
 */
interface IDataGridRenderer
{
	/**
	 * Provides complete data grid rendering.
	 * @param  DataGrid
	 * @return string
	 */
	function render(DataGrid $dataGrid);
	
}