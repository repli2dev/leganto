<?php
/**
 * @author Jan Papousek
 */
class MessageTest extends TableModelTest
{

	private $lng;

	private $role;
	
	private $userFrom;

	private $userTo;

	public function  __construct() {
		parent::__construct();
		// Language
		$lng = new Language();
		$this->lng = $lng->insert(array(
				Language::DATA_NAME => "kdsasasxnv",
				Language::DATA_LOCALE => "kdscadscsn"
		));
		// Role
		$roles = new Role();
		$this->role = $roles->insert(array(Role::DATA_NAME => "skdasdcsdcv"));
		// User
		$users = new Users();
		$this->userTo = $users->insert(array(
			Users::DATA_NICKNAME => "dccdsdcdscasdcaaa",
			Users::DATA_LANGUAGE => $this->lng,
			Users::DATA_PASSWORD => Users::passwordHash("aadscssdccaa", "aaa@aaa.aaa"),
			Users::DATA_EMAIL => "aaas@aaa.aaa",
			Users::DATA_ROLE => $this->role,
			Users::DATA_INSERTED => new DibiVariable("now()", "sql")
		));
		$this->userFrom = $users->insert(array(
			Users::DATA_NICKNAME => "dscaaaa",
			Users::DATA_LANGUAGE => $this->lng,
			Users::DATA_PASSWORD => Users::passwordHash("sdccaa", "aaa@aaa.aaa"),
			Users::DATA_EMAIL => "aaa@asdvv.aaa",
			Users::DATA_ROLE => $this->role,
			Users::DATA_INSERTED => new DibiVariable("now()", "sql")
		));
	}

	public function  __destruct() {
		dibi::query("TRUNCATE TABLE %n", Users::getTable());
		dibi::query("TRUNCATE TABLE %n", Language::getTable());
		dibi::query("TRUNCATE TABLE %n", Role::getTable());
	}

	protected function createInstance() {
		return new Message();
	}

	protected function getUpdate() {
		return array(Message::DATA_CONTENT => "bbb");
	}

	protected function getEntity() {
		return array(
			Message::DATA_CONTENT => "aaa",
			Message::DATA_INSERTED => new DibiVariable("now()", "sql"),
			Message::DATA_USER_TO => $this->userTo,
			Message::DATA_USER_FROM => $this->userFrom
		);
	}

	/** @Skip */
	protected function testInsertDuplicate() {}

}
