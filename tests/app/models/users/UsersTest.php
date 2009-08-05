<?php
/**
 * @author Jan Papousek
 */
class UsersTest extends TableModelTest
{

	public function  __construct() {
		parent::__construct();
		$lng = new Language();
		$lng->insert(array(
				Language::DATA_NAME => "kdsnv",
				Language::DATA_LOCALE => "kdsn"
		));
		$roles = new Role();
		$roles->insert(array(Role::DATA_NAME => "skdvh"));
	}

	public function  __destruct() {
		dibi::query("TRUNCATE TABLE %n", Language::getTable());
		dibi::query("TRUNCATE TABLE %n", Language::getTable());
	}

	protected function createInstance() {
		return new Users();
	}

	protected function getUpdate() {
		return array(Users::DATA_EMAIL => "aaa@bbb.aaa");
	}

	protected function getEntity() {
		return array(
			Users::DATA_NICKNAME => "aaa",
			Users::DATA_LANGUAGE => 1,
			Users::DATA_PASSWORD => Users::passwordHash("aaa", "aaa@aaa.aaa"),
			Users::DATA_EMAIL => "aaa@aaa.aaa",
			Users::DATA_ROLE => 1,
			Users::DATA_INSERTED => new DibiVariable("now()", "sql")
		);
	}

	protected function testAuthentication() {
		Environment::getUser()->signOut(TRUE);
		$this->assertFalse(Environment::getUser()->isAuthenticated());
		$this->getInstance()->insert($this->getEntity());
		try {
			Environment::getUser()->authenticate("lskhvjskcbgnkwecgneck gkhc kcsgj sc", "aaa");
			$this->fail();
		}
		catch (AuthenticationException $e) {
			if ($e->getCode() != IAuthenticator::IDENTITY_NOT_FOUND) {
				$this->fail();
			}
		}
		try {
			Environment::getUser()->authenticate("aaa@aaa.aaa", "ksjd");
			$this->fail();
		}
		catch(AuthenticationException $e) {
			if ($e->getCode() != IAuthenticator::INVALID_CREDENTIAL) {
				$this->fail();
			}
		}
		Environment::getUser()->authenticate("aaa@aaa.aaa", "aaa");
		$this->assertTrue(Environment::getUser()->isAuthenticated());
		Environment::getUser()->signOut(TRUE);
	}
}
