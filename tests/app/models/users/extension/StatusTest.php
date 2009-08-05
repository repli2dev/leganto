<?php
/**
 * @author Jan Papousek
 */
class StatusTest extends TableModelTest
{

	private $lng;

	private $role;

	private $user;

	public function  __construct() {
		parent::__construct();
		// Language
		$lng = new Language();
		$this->lng = $lng->insert(array(
				Language::DATA_NAME => "kdsasxasxnv",
				Language::DATA_LOCALE => "kdascadscsn"
		));
		// Role
		$roles = new Role();
		$this->role = $roles->insert(array(Role::DATA_NAME => "skdasdcsdcvh"));
		// User
		$users = new Users();
		$this->user = $users->insert(array(
			Users::DATA_NICKNAME => "dccdsdcdscaaaa",
			Users::DATA_LANGUAGE => 1,
			Users::DATA_PASSWORD => Users::passwordHash("aadscsdcsdccaa", "aaa@aaa.aaa"),
			Users::DATA_EMAIL => "aaa@aaa.aaa",
			Users::DATA_ROLE => 1,
			Users::DATA_INSERTED => new DibiVariable("now()", "sql")
		));
	}

	public function  __destruct() {
		dibi::query("TRUNCATE TABLE %n", Users::getTable());
		dibi::query("TRUNCATE TABLE %n", Language::getTable());
		dibi::query("TRUNCATE TABLE %n", Role::getTable());
	}

	protected function createInstance() {
		return new Status();
	}

	protected function getUpdate() {
		return array(Status::DATA_CONTENT => "aaa");
	}

	protected function getEntity() {
		return array(
			Status::DATA_USER => $this->user,
			Status::DATA_LANGUAGE => $this->lng,
			Status::DATA_INSERTED => new DibiVariable("now()", "sql"),
			Status::DATA_CONTENT => "bbb"
		);
	}

	/** @Skip */
	protected function testInsertDuplicate() {}

	protected function testGetCurrent() {
		$this->getInstance()->insert($this->getEntity());
		$row = $this->getInstance()->getCurrent($this->user);
		$this->assertEquals("bbb", $row[Status::VIEW_CONTENT]);
		$this->assertEquals($this->user, $row[Status::VIEW_USER_ID]);
	}

}
