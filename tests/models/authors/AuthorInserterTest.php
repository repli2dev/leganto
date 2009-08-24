<?php
/**
 * @author Jan Papousek
 */
class AuthorInserterTest extends EskymoTestCase
{

	private $author = array(
		"type"			=> AuthorEntity::GROUP,
		"group_name"	=> "sejuvhsidfgvsidfgv",
		"inserted"		=> "2010-10-10 10:10:10"
	);

	public function  __destruct() {
		$this->startUp();
	}

	protected function startUp() {
		SimpleTableModel::createTableModel("author")->deleteAll($this->author);
	}

	protected function testInsert() {
		$author = Leganto::authors()->createEmpty();
		$author->type		= $this->author["type"];
		$author->groupname	= $this->author["group_name"];
		$author->inserted	= $this->author["inserted"];
		$id = Leganto::authors()->getInserter()->insert($author);
		if (empty($id)) {
			$this->fail();
		}
		if ($id == -1) {
			$this->fail();
		}
		$inserted = Leganto::authors()->getSelector()->find($id);
		$this->assertEquals($author["type"], $inserted->type);
		$this->assertEquals($author["group_name"], $inserted->groupname);
	}

}
