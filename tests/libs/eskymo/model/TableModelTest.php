<?php
/**
 * @author Jan Papousek
 */
abstract class TableModelTest extends EskymoTestCase
{

	/**
	 * Tested class
	 *
	 * @var ITableModel
	 */
	protected $tested;

	private static $wrongInput = array("aacdjsdbvbkdbsjv" => "svbkjdsbvkdsbv");

	public function  __construct() {
		if (count($this->getEntity()) == 0) {
			throw new InvalidStateException("The method '".$this->getClass()."->getEntity()' returns empty array.");
		}
		if (count($this->getUpdate()) != 1) {
			throw new InvalidStateException("The method '".$this->getClass()."->getUpdate()' does not return array containg one key.");
		}
		$table = call_user_func(array(
			get_class($this->getInstance()),
			"getTable"
		));
		dibi::query("TRUNCATE TABLE %n", $table);
	}

	/**
	 * It returns a new istance of the tested class
	 *
	 * @return ITableModel
	 */
	abstract protected function createInstance();

	/**
	 * It returns an entity which the test will work with
	 *
	 * The array has to longer at least 1 key
	 *
	 * @return array Column => value
	 */
	abstract protected function getEntity();

	/**
	 * It returns a entity change which will be used for testing
	 *
	 * The array has to contain just 1 key
	 *
	 * @return array Column => valus
	 */
	abstract protected function getUpdate();

	/**
	 * It returns an instance of the tested class
	 *
	 * @return ITableModel
	 * @throws InvalidStateException if there is no tested class implenting ITableModel
	 */
	protected function getInstance() {
		if (empty($this->tested)) {
			$this->tested = $this->createInstance();
		}
		return $this->tested;
	}

	protected function tearDown() {
		$this->tested = NULL;
		$table = call_user_func(array(
			get_class($this->getInstance()),
			"getTable"
		));
		dibi::query("TRUNCATE TABLE %n", $table);
	}

################################################################################
# Common tests
################################################################################

	/**
	 * It tries to insert an entity adn delete it by its ID.
	 */
	protected function testDelete() {
		$id = $this->getInstance()->insert($this->getEntity());
		$this->getInstance()->delete($id);
		try {
			$this->getInstance()->find($id);
			$this->fail("The deleted entity exists.");
		}
		catch (DataNotFoundException $e) {}
	}

	/**
	 * It tries to insert an entity and delete it by some column
	 */
	protected function testDeleteAll() {
		$cond = array(
			ExtraArray::firstKey($this->getEntity()) => ExtraArray::firstKey($this->getEntity())
		);
		$id = $this->getInstance()->insert($this->getEntity());
		$this->assertEquals(1, $this->getInstance()->deleteAll($cond), "Deleting of one entity does not return 1");
		try {
			$this->getInstance()->find($id);
			$this->fail("The deleted entity exists.");
		}
		catch (DataNotFoundException $e) {}
	}

	protected function testInsertDuplicate() {
		$this->getInstance()->insert($this->getEntity());
		$this->assertEquals(-1,$this->getInstance()->insert($this->getEntity()), "Inserting of the duplicate entity should return -1");
	}

	/**
	 * It tries to insert an entity
	 */
	protected function testInsert() {
		try {
		$id = $this->getInstance()->insert($this->getEntity());

		if (empty($id)) {
			$this->fail("The returned id of inserted entity is empty.");
		}
		if ($id == -1) {
			$this->fail("The returned id of inserted entity is -1, expected positive number.");
		}
		$row = $this->getInstance()->find($id);
		if (empty($row)) {
			$this->fail("The inserted entity does not exist");
		}
		foreach($this->getEntity() AS $key => $value) {
			if (!isset($row[$key])) {
				$this->fail("The inserted entity is different from the original, key '$key' does not exist.");
			}
		}
		}
		catch (Exception $e) {
			Debug::paintBlueScreen($e);
		}
	}

	protected function testUpdate() {
		$id = $this->getInstance()->insert($this->getEntity());
		$this->getInstance()->update($id, $this->getUpdate());
		$row = $this->getInstance()->find($id);
		$key = ExtraArray::firstKey($this->getUpdate());
		$value = ExtraArray::firstValue($this->getUpdate());
		if ($row[$key] != $value) {
			$this->fail("The column [$key] of the updated entity has value '".$row[$key]."', expected '$value'");
		}
	}

	protected function testUpdateAll() {
		$id = $this->getInstance()->insert($this->getEntity());
		$cond = array(
			ExtraArray::firstKey($this->getEntity()) => ExtraArray::firstKey($this->getEntity())
		);
		$this->getInstance()->updateAll($cond, $this->getUpdate());
		$row = $this->getInstance()->find($id);
		$key = ExtraArray::firstKey($this->getUpdate());
		$value = ExtraArray::firstValue($this->getUpdate());
		if ($row[$key] != $value) {
			$this->fail("The column [$key] of the updated entity has value '".$row[$key]."', expected '$value'");
		}
	}

################################################################################
# Exceptions
################################################################################

	/**
	 * @TestThrow(NullPointerException)
	 */
	protected function testDeleteEmpty() {
		$this->getInstance()->delete(NULL);
	}

	/**
	 * @TestThrow(NullPointerException)
	 */
	protected function testInsertEmpty() {
		$this->getInstance()->insert(array());
	}

	/**
	 * @TestThrow(NullPointerException)
	 */
	protected function testInsertNotAllRequiredColumns() {
		$this->getInstance()->insert(self::$wrongInput);
	}

	/**
	 * @TestThrow(DibiDriverException)
	 */
	protected function testInsertWrong() {
		$this->getInstance()->insert(self::$wrongInput + $this->getEntity());
	}

	/**
	 * @TestThrow(NullPointerException)
	 */
	protected function testUpdateEmptyId() {
		$this->getInstance()->update(NULL, $this->getUpdate());
	}

	/**
	 * @TestThrow(NullPointerException)
	 */
	protected function testUpdateEmptyRequiredColumn() {
		$id = $this->getInstance()->insert($this->getEntity());
		$input = $this->getEntity();
		$key = ExtraArray::firstKey($this->getUpdate());
		$input[$key] = NULL;
		$this->getInstance()->update($id, $input);
	}

	/**
	 * @TestThrow(NullPointerException)
	 */
	protected function testUpdateAllRequiredColumn() {
		$input = $this->getEntity();
		$key = ExtraArray::firstKey($this->getUpdate());
		$input[$key] = NULL;
		$this->getInstance()->updateAll(array(), $input);
	}

}