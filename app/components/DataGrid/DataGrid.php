<?php

/**
 * This source file is subject to the "Nette license" that is bundled
 * with this package in the file license.txt.
 *
 * For more information please see http://nettephp.com
 *
 * @author     Roman Sklenář
 * @copyright  Copyright (c) 2009 Roman Sklenář (http://romansklenar.cz)
 * @license    http://nettephp.com/license  Nette license
 * @link       http://nettephp.com/extras/datagrid
 */



/**
 * A data bound list control that displays the items from data source in a table.
 * The DataGrid control allows you to select, sort, and manage these items.
 * 
 * <code>
 * $grid = new DataGrid;
 * $grid->bindDataTable($model->findAll($model->table)->toDataSource());
 * 
 * $grid->addColumn('column', 'Column caption')->addFilter();
 * $grid['column']->getCellPrototype()->style('text-align: center');
 * 
 * $grid->addActionColumn('Actions');
 * $grid->addAction('Edit', 'Item:edit');
 * 
 * $presenter->addComponent($grid, 'componentName');
 * </code>
 *
 * @author     Roman Sklenář
 * @copyright  Copyright (c) 2009 Roman Sklenář
 * @example    http://nettephp.com/extras/datagrid
 * @package    Nette\Extras\DataGrid
 * @version    $Id: DataGrid.php 18 2009-05-20 22:41:25Z mail@romansklenar.cz $
 */
class DataGrid extends Control implements ArrayAccess, INamingContainer
{
	/** @persistent int */
	public $page = 1;

	/** @persistent string */
	public $order = '';

	/** @persistent string */
	public $filters = '';

	/** @var DibiDataSource */
	protected $dataSource;

	/** @var Paginator */
	protected $paginator;

	/** @var bool  multi column order */
	public $multiOrder = TRUE;

	/** @var array */
	public $operations = array();

	/** @var array  of valid callback(s) */
	protected $onOperationSubmit;

	/** @var IDataGridRenderer */
	protected $renderer;

	/** @var string */
	protected $keyName;
	
	/** @var array */
	protected $receivedSignals = array();
	
	/** @var bool  was method render() called? */
	protected $wasRendered = FALSE;
	
	/** @var ITranslator */
	protected $translator;


	/**
	 * Data grid constructor.
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->paginator = new Paginator;
		$this->setItemsPerPage(15);
		
		$this->addComponent(new ComponentContainer, 'columns');
		$this->addComponent(new ComponentContainer, 'filters');
		$this->addComponent(new ComponentContainer, 'actions');
	}


	/**
	 * Binds data source to data grid.
	 * @param DibiDataSource
	 * @throws DibiException
	 * @return void
	 */
	public function bindDataTable(DibiDataSource $dataSource)
	{
		$this->dataSource = $dataSource;
		$this->paginator->itemCount = count($dataSource);
	}


	/**
	 * Getter / property method.
	 * @return DibiDataSource
	 */
	public function getDataSource()
	{
		return $this->dataSource;
	}


	/**
	 * Getter / property method.
	 * @return DibiIndexInfo
	 */
	public function getKeyName()
	{
		if ($this->keyName != NULL) {
			return $this->keyName;
		}
		throw new InvalidStateException("Name of key for group operations or actions was not set for DataGrid '" . $this->getName() . "'.");
	}


	/**
	 * Setter / property method.
	 * Key name must be set if you want to use group operations or actions.
	 * @param  string  column name used to identifies each item/record in data grid (name of primary key of table/query from data source is recomended)
	 * @return void
	 */
	public function setKeyName($key)
	{
		$this->keyName = $key;
	}



	/********************* public getters and setters *********************/



	/**
	 * Setter / property method.
	 * Defines number of rows per one page on the grid.
	 * @param  int
	 * @throws InvalidArgumentException
	 * @return void
	 */
	public function setItemsPerPage($value)
	{
		if ($value <= 0) {
			throw new InvalidArgumentException("Parametr must be positive number, '$value' given.");
		}
		$this->paginator->itemsPerPage = (int) $value;
	}


	/**
	 * Getter / property method.
	 * @return int
	 */
	public function getItemsPerPage()
	{
		return $this->paginator->itemsPerPage;
	}


	/**
	 * Getter / property method.
	 * Generates list of pages used for visual control. Use for your custom paginator rendering.
	 * @return array
	 */
	public function getSteps($count = 15)
	{
		// paginator steps
		$arr = range(max($this->paginator->firstPage, $this->page - 3), min($this->paginator->lastPage, $this->page + 3));
		$quotient = ($this->paginator->pageCount - 1) / $count;
		for ($i = 0; $i <= $count; $i++) {
			$arr[] = round($quotient * $i) + $this->paginator->firstPage;
		}
		sort($arr);

		return array_values(array_unique($arr));
	}
	
	
	/**
	 * Setter / property method.
	 * @return Paginator
	 */
	public function getPaginator()
	{
		return $this->paginator;
	}


	/**
	 * Setter / property method.
	 * @param  mixed  callback(s) to handler(s) which is called after data grid form operation is submited.
	 * @return void
	 */
	public function setOnOperationSubmit($callback)
	{
		if (!is_array($this->onOperationSubmit)) {
			$this->onOperationSubmit = array();
		}
		$this->onOperationSubmit[] = $callback;
	}


	/**
	 * Getter / property method.
	 * @return array
	 */
	public function getOnOperationSubmit()
	{
		return $this->onOperationSubmit;
	}



	/********************* Iterators getters *********************/



	/**
	 * Iterates over datagrid rows.
	 * @throws InvalidStateException
	 * @return DibiResultIterator
	 */
	public function getRows()
	{
		if (!$this->dataSource instanceof DibiDataSource) {
			throw new InvalidStateException("Data source has not been set or has invalid data type. You must set data source before you want get rows.");
		}
		return $this->dataSource->getIterator();
	}


	/**
	 * Iterates over all datagrid columns.
	 * @throws InvalidArgumentException
	 * @return ArrayIterator
	 */
	public function getColumns()
	{
		return $this->getComponent('columns', TRUE)->getComponents(FALSE, 'IDataGridColumn');
	}	


	/**
	 * Iterates over all datagrid filters.
	 * @throws InvalidArgumentException
	 * @return ArrayIterator
	 */
	public function getFilters()
	{
		return $this->getComponent('filters', TRUE)->getComponents(FALSE, 'IDataGridColumnFilter');
	}


	/**
	 * Iterates over all datagrid actions.
	 * @throws InvalidArgumentException
	 * @return ArrayIterator
	 */
	public function getActions()
	{
		return $this->getComponent('actions', TRUE)->getComponents(FALSE, 'IDataGridAction');
	}



	/********************* general data grid behavior *********************/



	/**
	 * Does data grid has any column?
	 * @return bool
	 */
	public function hasColumns()
	{
		return count($this->getColumns()->getInnerIterator()) > 0;
	}


	/**
	 * Does any of datagrid columns has a filter?
	 * @return bool
	 */
	public function hasFilters()
	{
		return count($this->getFilters()->getInnerIterator()) > 0;
	}


	/**
	 * Does datagrid has any action?
	 * @return bool
	 */
	public function hasActions()
	{
		return count($this->getActions()->getInnerIterator()) > 0;
	}


	/**
	 * Does datagrid has any group operation?
	 * @return bool
	 */
	public function hasOperations()
	{
		return count($this->operations) > 0;
	}



	/********************* signal handlers ********************/



	/**
	 * Changes page number.
	 * @param  int
	 * @return void
	 */
	public function handlePage($page)
	{
		$this->paginator->page = $this->page = ($page > 0 ? $page : 1);
		$this->invalidateControl('grid');
		if (!$this->presenter->isAjax()) $this->presenter->redirect('this');
	}


	/**
	 * Changes column sorting order.
	 * @param  string
	 * @return void
	 */
	public function handleOrder($by)
	{
		parse_str($this->order, $list);
		
		if (!isset($list[$by])) {
			if (!$this->multiOrder) {
				$list = array();
			}
			$list[$by] = 'a';
		
		} elseif ($list[$by] === 'd') {
			if ($this->multiOrder) {
				unset($list[$by]);
			} else {
				$list[$by] = 'a';
			}
		
		} else {
			$list[$by] = 'd';
		}
		
		$this->order = http_build_query($list, '', '&');
		$this->invalidateControl('grid');
		if (!$this->presenter->isAjax()) $this->presenter->redirect('this');
	}


	/**
	 * Prepare filtering.
	 * @param  string
	 * @return void
	 */
	public function handleFilter($by)
	{
		$filters = array();
		foreach ($by as $key => $value) {
			if ($value !== '') $filters[$key] = $value;
		}
		$this->filters = http_build_query($filters, '', '&');
		$this->invalidateControl('grid');
		if (!$this->presenter->isAjax()) $this->presenter->redirect('this');
	}



	/********************* submit handlers *********************/



	/**
	 * Data grid form submit handler.
	 * @param  AppForm
	 * @return void
	 */
	public function onSubmitHandler(AppForm $form)
	{
		$this->receivedSignals[] = 'submit';
		
		// was form submitted?
		if ($form->isSubmitted()) {
			$values = $form->getValues();
			
			if ($form['filterSubmit']->isSubmittedBy()) {
				$this->receivedSignals[] = 'filter';
				$this->handleFilter($values['filters']);
				
			} elseif ($form['pageSubmit']->isSubmittedBy()) {
				$this->receivedSignals[] = 'page';
				$this->handlePage($values['page']);
					
			} elseif ($form['operationSubmit']->isSubmittedBy()) {
				trigger_error('No user defined handler for group operations; assign valid callback to your group operations handler into DataGrid::$operationsHandler variable.', E_USER_WARNING);
				return;
			
			} else {
				// unknown submit button
				throw new InvalidStateException("Unknown submit button.");
			}
		
		}
		if (!$this->presenter->isAjax()) $this->presenter->redirect('this');
	}


	/**
	 * Filter handler. Left functionality on method onSubmitHandler.
	 * @param  Button
	 * @return void
	 */
	public function onClickFilterHandler(Button $button)
	{
		$this->onSubmitHandler($button->getParent());
	}
	
	
	/**
	 * Change page handler. Left functionality on method onSubmitHandler.
	 * @param  Button
	 * @return void
	 */
	public function onClickPageHandler(Button $button)
	{
		$this->onSubmitHandler($button->getParent());
	}



	/********************* applycators (call before rendering only) *********************/



	/**
	 * Applies paging on data grid.
	 * @return void
	 */
	protected function applyPaging()
	{
		$this->paginator->page = $this->page;
		$this->paginator->itemCount = count($this->dataSource);
		if ($this->wasRendered && $this->paginator->itemCount < 1 && !empty($this->filters)) {
			// NOTE: don't use flash messages (because you can't - header already sent)
			$this->getTemplate()->flashes[] = (object) array(
				'message' => $this->translate("Used filters did not match any items."),
				'type' => 'info',
			);
		}
		$this->dataSource->applyLimit($this->paginator->length, $this->paginator->offset);
	}


	/**
	 * Applies sorting on data grid.
	 * @return void
	 */
	protected function applySorting()
	{
		$i = 1;
		parse_str($this->order, $list);
		foreach ($list as $field => $dir) {
			$this->dataSource->orderBy($field, $dir === 'a' ? dibi::ASC : dibi::DESC);
			$list[$field] = array($dir, $i++);
		}
		return $list;
	}


	/**
	 * Applies filtering on data grid.
	 * @return void
	 */
	protected function applyFiltering()
	{
		if (!$this->hasFilters()) return;
		
		parse_str($this->filters, $list);
		foreach ($list as $column => $value) {
			if ($value !== '') {
				$this->getComponent('columns', TRUE)->getComponent($column, TRUE)->applyFilter($value);
			}
		}
	}



	/********************* renderers *********************/



	/**
	 * Sets data grid renderer.
	 * @param  IDataGridRenderer
	 * @return void
	 */
	public function setRenderer(IDataGridRenderer $renderer)
	{
		$this->renderer = $renderer;
	}


	/**
	 * Returns data grid renderer.
	 * @return IDataGridRenderer|NULL
	 */
	public function getRenderer()
	{
		if ($this->renderer === NULL) {
			$this->renderer = new DataGridRenderer;
		}
		return $this->renderer;
	}


	/**
	 * Renders data grid.
	 * @return void
	 */
	public function render()
	{
		if (!$this->wasRendered) {
			$this->wasRendered = TRUE;
			
			if (!$this->hasColumns()) {
				// auto-generate columns
				$ds = clone $this->dataSource;
				$row = $ds->select('*')->fetch();
				$keys = array_keys((array)$row);
				foreach ($keys as $key) $this->addColumn($key);
			}
			
			// filter items (must be in this order)
			$this->applyFiltering();
			$this->applySorting();
			$this->applyPaging();
			
			if ($this->isSignalReceiver('filter') || $this->isSignalReceiver('page')) {
				$this->regenerateFormControls($this->getForm(TRUE));
			}
		}

		$args = func_get_args();
		array_unshift($args, $this);
		$s = call_user_func_array(array($this->getRenderer(), 'render'), $args);
		
		echo mb_convert_encoding($s, 'HTML-ENTITIES', 'UTF-8');
	}
	
	
	/**
	 * Template factory.
	 * @return ITemplate
	 */
	protected function createTemplate()
	{
		$template = parent::createTemplate();
		if ($this->getTranslator() !== NULL) {
			$template->setTranslator($this->getTranslator());
		}
		return $template;
	}



	/********************* components handling *********************/



	/**
	 * Component factory.
	 * @see Nette/ComponentContainer#createComponent()
	 */
	protected function createComponent($name)
	{
		switch ($name) {
			case 'form':
				// NOTE: signal-submit on form disregard component's state
				//		because form is created directly by Presenter in signal handling phase
				//		and this principle is used to detect submit signal
				if (!$this->wasRendered) {
					$this->receivedSignals[] = 'submit';
				}

				$form = new AppForm($this, $name);
				$form->setTranslator($this->getTranslator());
				FormControl::$idMask = 'frm-datagrid-' . String::capitalize($this->getName()) . '-%s-%s';
				
				$form->addSubmit('filterSubmit', 'Apply filters')
					->onClick[] = array($this, 'onClickFilterHandler');
				
				$form->addSelect('operations', 'Selected:', $this->operations);
				$form->addSubmit('operationSubmit', 'Send')->onClick = $this->onOperationSubmit;
				
				// page input
				$form->addText('page', 'Page', 1);
				$form['page']->setValue($this->paginator->page);
				$form->addSubmit('pageSubmit', 'Change page')
					->onClick[] = array($this, 'onClickPageHandler');

				
				// generate filters FormControls
				if ($this->hasFilters()) {
					$sub = $form->addContainer('filters');
					foreach ($this->getFilters() as $filter) {
						$sub->addComponent($filter->getFormControl(), $filter->getName());
						// NOTE: must be setted after is FormControl conntected to the form
						$sub->getComponent($filter->getName(), TRUE)->setValue($filter->getValue());
					}
				}
				
				// checker
				if ($this->hasOperations()) {
					$primary = $this->getKeyName();
					$sub = $form->addContainer('checker');
					
					if ($this->isSignalReceiver('submit')) {
						$ds = clone $this->dataSource;
						$this->applyFiltering();
						$this->applySorting();
						$this->applyPaging();
					}
					
					foreach ($this->getRows() as $row) {
						$sub->addCheckbox($row[$primary], $row[$primary]);
					}
					
					if ($this->isSignalReceiver('submit')) $this->dataSource = $ds;
				}
				
				$renderer = $form->getRenderer();
				$renderer->wrappers['controls']['container'] = NULL;
				$renderer->wrappers['label']['container'] = NULL;
				$renderer->wrappers['control']['container'] = NULL;
				$form->setRenderer($renderer);
				return;
				
			default:
				parent::createComponent($name);
				return;
		}
	}


	/**
	 * Returns data grid's form component.
	 * @param  bool   throw exception if form doesn't exist?
	 * @return AppForm
	 */
	public function getForm($need = TRUE)
	{
		return $this->getComponent('form', $need);
	}


	/**
	 * Generates filter controls and checker's checkbox controls
	 * @param  AppForm
	 * @return void
	 */
	protected function regenerateFormControls(AppForm $form)
	{
		// regenerate checker's checkbox controls
		if ($this->hasOperations()) {
			$primary = $this->getKeyName();
			$form->removeComponent($form['checker']);
			$sub = $form->addContainer('checker');
			foreach ($this->getRows() as $row) {
				$sub->addCheckbox($row[$primary], $row[$primary]);
			}
		}
		
		// for selectbox filter controls update values if was filtered over column
		if ($this->hasFilters() && $this->isSignalReceiver('filter')) {
			foreach ($this->getFilters() as $filter) {
				if ($filter instanceof SelectboxFilter) {
					$filter->generateItems();
				}
			}
		}
		
		// page input
		$form['page']->setValue($this->paginator->page);
	}


	/**
	 * Allows group operations and adds checker (column filled by checkboxes).
	 * @param  array  list of group operations (selectbox items)
	 * @param  mixed  valid callback handler which provides rutines from $operations
	 * @param  string column name used to identifies each item/record in data grid (name of primary key of table/query from data source is recomended)
	 * @return void
	 */
	public function allowOperations(array $operations, $callback = NULL, $key = NULL)
	{
		$this->operations = $operations;
		
		if ($key != NULL && $this->keyName == NULL) {
			$this->setKeyName($key);
		}
		if ($callback != NULL && $this->onOperationSubmit == NULL) {
			 $this->setOnOperationSubmit($callback);
		}
	}



	/********************* component factories *********************/



	/**
	 * Adds column of textual values.
	 * @param  string  control name
	 * @param  string  column label
	 * @param  int     maximum number of dislayed characters
	 * @return TextColumn
	 */
	public function addColumn($name, $caption = NULL, $maxLength = NULL)
	{
		return $this[$name] = new TextColumn($caption, $maxLength);
	}


	/**
	 * Adds column of numeric values.
	 * @param  string  control name
	 * @param  string  column label
	 * @param  int     number of digits after the decimal point
	 * @return NumericColumn
	 */
	public function addNumericColumn($name, $caption = NULL, $precision = 2)
	{
		return $this[$name] = new NumericColumn($caption, $precision);
	}


	/**
	 * Adds column of date-represented values.
	 * @param  string  control name
	 * @param  string  column label
	 * @param  string  date format
	 * @return DateColumn
	 */
	public function addDateColumn($name, $caption = NULL, $format = '%x')
	{
		return $this[$name] = new DateColumn($caption, $format);
	}


	/**
	 * Adds column of boolean values (represented by checkboxes).
	 * @param  string  control name
	 * @param  string  column label
	 * @return CheckboxColumn
	 */
	public function addCheckboxColumn($name, $caption = NULL)
	{
		return $this[$name] = new CheckboxColumn($caption);
	}


	/**
	 * Adds column of graphical images.
	 * @param  string  control name
	 * @param  string  column label
	 * @return ImageColumn
	 */
	public function addImageColumn($name, $caption = NULL)
	{
		return $this[$name] = new ImageColumn($caption);
	}


	/**
	 * Adds column which provides moving entries up or down.
	 * @param  string  control name
	 * @param  string  column label
	 * @param  string  destination or signal to handler which do the move rutine
	 * @param  array   textual labels for generated links
	 * @param  bool    use ajax? (add class DataGridColumn::$ajaxClass into generated link)
	 * @return PositionColumn
	 */
	public function addPositionColumn($name, $caption = NULL, $destination = NULL, array $moves = NULL, $useAjax = TRUE)
	{
		return $this[$name] = new PositionColumn($caption, $destination, $moves);
	}


	/**
	 * Adds column which represents logic container for data grid actions.
	 * @param  string  column label
	 * @return ActionColumn
	 */
	public function addActionColumn($caption)
	{
		return $this['actions'] = new ActionColumn($caption);
	}


	/**
	 * Action factory.
	 * @param  string  textual title
	 * @param  string  textual link destination
	 * @param  Html    element which is added to a generated link
	 * @param  bool    use ajax? (add class self::$ajaxClass into generated link)
	 * @param  bool    generate link with argument? (variable $keyName must be defined in data grid)
	 * @return DataGridAction
	 */
	public function addAction($title, $signal, $icon = NULL, $useAjax = FALSE, $type = DataGridAction::WITH_KEY)
	{
		if (!$this->getComponent('columns', TRUE)->getComponent('actions', FALSE)) {
			trigger_error('Use DataGrid::addActionColumn before you add actions.', E_USER_WARNING);
		}
		$count = $this->hasActions() ? count($this->getActions()->getInnerIterator()) : 0;
		$action = new DataGridAction($title, $signal, $icon, $useAjax, $type);
		$this->getComponent('actions', TRUE)->addComponent($action, (string)$count);
		return $action;
	}
	
	
	
	/********************* translator ********************/



	/**
	 * Sets translate adapter.
	 * @param  ITranslator
	 * @return void
	 */
	public function setTranslator(ITranslator $translator = NULL)
	{
		$this->translator = $translator;
	}


	/**
	 * Returns translate adapter.
	 * @return ITranslator|NULL
	 */
	final public function getTranslator()
	{
		return $this->translator;
	}


	/**
	 * Returns translated string.
	 * @param  string
	 * @return string
	 */
	public function translate($s)
	{
		$args = func_get_args();
		return $this->translator === NULL ? $s : call_user_func_array(array($this->getTranslator(), 'translate'), $args);
	}



	/********************* interface \ArrayAccess *********************/



	/**
	 * Adds the component to the container.
	 * @param  string  component name
	 * @param  IComponent
	 * @return void.
	 */
	final public function offsetSet($name, $component)
	{
		$this->getComponent('columns', TRUE)->addComponent($component, $name);
	}


	/**
	 * Returns component specified by name. Throws exception if component doesn't exist.
	 * @param  string  component name
	 * @return IComponent
	 * @throws InvalidArgumentException
	 */
	final public function offsetGet($name)
	{
		return $this->getComponent('columns', TRUE)->getComponent($name, TRUE);
	}


	/**
	 * Does component specified by name exists?
	 * @param  string  component name
	 * @return bool
	 */
	final public function offsetExists($name)
	{
		return $this->getComponent('columns', TRUE)->getComponent($name, FALSE) !== NULL;
	}


	/**
	 * Removes component from the container. Throws exception if component doesn't exist.
	 * @param  string  component name
	 * @return void
	 */
	final public function offsetUnset($name)
	{
		$component = $this->getComponent('columns', TRUE)->getComponent($name, FALSE);
		if ($component !== NULL) {
			$this->getComponent('columns', TRUE)->removeComponent($component);
		}
	}
	
	
	/********************* interface \ISignalReceiver *********************/


	/**
	 * Calls signal handler method.
	 * @param  string
	 * @return void
	 */
	public function signalReceived($signal)
	{
		$this->receivedSignals[] = $signal;
		parent::signalReceived($signal);
	}
	
	
	/**
	 * Checks if control received a signal.
	 * @param  string  signal name
	 * @return bool
	 */
	public function isSignalReceiver($signal = NULL)
	{
		if ($signal === NULL) { 
			return count($this->receivedSignals) > 0;
		} else {
			return in_array($signal, $this->receivedSignals);
		}
	}


	/**
	 * Renders table grid and return as string.
	 * @return string
	 */
	public function __toString()
	{
		$s = call_user_func_array(array($this->getRenderer(), 'render'), array($this));
		return mb_convert_encoding($s, 'HTML-ENTITIES', 'UTF-8');
	}
}