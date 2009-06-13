<?php

require_once dirname(__FILE__) . '/../DataGridColumnFilter.php';



/**
 * Representation of data grid column checkbox filter.
 *
 * @author     Roman Sklenář
 * @copyright  Copyright (c) 2009 Roman Sklenář
 * @example    http://nettephp.com/extras/datagrid
 * @package    Nette\Extras\DataGrid
 * @version    $Id: CheckboxFilter.php 7 2009-05-02 22:09:15Z RSklenar@seznam.cz $
 */
class CheckboxFilter extends DataGridColumnFilter
{
	/**
	 * Returns filter's form element.
	 * @return FormControl
	 */
	public function getFormControl()
	{
		if ($this->element instanceof FormControl) return $this->element;
		$element = new Checkbox($this->getName());
		
		return $this->element = $element;
	}
}