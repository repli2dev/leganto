<?php

/**
 * Defines method that must be implemented to allow a component act like a data grid column.
 *
 * @author     Roman Sklenář
 * @copyright  Copyright (c) 2009 Roman Sklenář
 * @package    Nette\Extras\DataGrid
 * @version    $Id: IDataGridColumn.php 5 2009-05-02 21:38:31Z RSklenar@seznam.cz $
 */
interface IDataGridColumn
{
	/**
	 * Is column orderable?
	 * @return bool
	 */
	function isOrderable();
	
	
	/**
	 * Gets header link (order signal)
	 * @return string
	 */
	function getLink();
	
	
	/**
	 * Has column filter box?
	 * @return bool
	 */
	function hasFilter();
	
	
	/**
	 * Returns column's filter.
	 * @return IDataGridColumnFilter|NULL
	 */
	function getFilter();
	
	
	/**
	 * Formats cell's content.
	 * @param  mixed
	 * @return string
	 */
	function formatContent($value);
	
	
	/**
	 * Filters data source.
	 * @param  mixed
	 * @return void
	 */
	function applyFilter($value);

}