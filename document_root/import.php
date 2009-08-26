<?php
// absolute filesystem path to the web root
define('WWW_DIR', dirname(__FILE__));

// absolute filesystem path to the application root
define('APP_DIR', WWW_DIR . '/../app');

// absolute filesystem path to the libraries
define('LIBS_DIR', WWW_DIR . '/../libs');

require_once LIBS_DIR . '/Nette/loader.php';

// Loader
$loader = new RobotLoader();
$loader->addDirectory(APP_DIR);
$loader->addDirectory(LIBS_DIR);
$loader->register();

Debug::enable(Debug::DEVELOPMENT);

// IMPORT CLASS

class Import extends EskymoObject
{

	private $settings = array(
		"tables"		=> FALSE,
		"language"		=> FALSE,
		"role"			=> FALSE,
		"authors"		=> FALSE,
		"books"			=> FALSE,
		"tags"			=> FALSE,
		"tagged"		=> FALSE,
		"users"			=> FALSE,
		"opinions"		=> FALSE,
		"wanted"		=> FALSE,
		"discussion"	=> TRUE
	);

	private $language = 1;

	private $role = 1;

	public function  __construct() {
		// DATABASE
		$source = new Config();
		$source->add("host", "databases.savana.cz:13305");
		$source->add("database", "reader");
		$source->add("username", "reader");
		$source->add("password", "terka90");

		$destination = new Config();
		$destination->add("host", "databases.savana.cz:13307");
		$destination->add("database", "preader_devel");
		$destination->add("username", "preader_devel");
		$destination->add("password", "Hublu.Mer");

		dibi::connect($source, "source");
		dibi::connect($destination, "destination");

		dibi::activate("destination");
		dibi::query("SET CHARACTER SET utf8");
	}

	public function import() {
		foreach (array_keys($this->settings) AS $toImport) {
			if (!empty($this->settings[$toImport])) {
				call_user_func(array($this, "import" . ucfirst($toImport)));
				echo "\n$toImport DONE\n";
			}
			else {
				echo "\n$toImport SKIPPED\n";
			}
		}
	}

	private function importAuthors() {
		dibi::activate("source");
		$rows = dibi::query("SELECT * FROM [reader_writer] ORDER BY [id]")->fetchAll();

		dibi::activate("destination");
		dibi::begin();

		foreach ($rows AS $row) {
			$parts = explode(" ", $row["name"]);
			$lastname = trim($parts[0]);
			$firstname = "";
			for($i=1; $i<count($parts); $i++) {
				$firstname .= $parts[$i] . " ";
			}
			$firstname = trim($firstname);
			dibi::insert("author", array(
					'type'			=> 'person',
					'first_name'	=> $firstname,
					'last_name'		=> $lastname,
					'inserted'		=> new DibiVariable("now()", "sql"),
					'id_author'		=> $row["id"]
				))->execute();
			echo ".";
		}

		dibi::commit();
	}

	private function importBooks() {
		dibi::activate("source");
		$rows = dibi::query("SELECT * FROM [reader_book] ORDER BY [id]")->fetchAll();

		dibi::activate("destination");
		dibi::begin();
		foreach ($rows AS $row) {
			// BOOK
			dibi::insert("book", array(
					'inserted'		=> new DibiVariable("now()", "sql"),
					'id_book'		=> $row["id"]
				))->execute();
			// TITLE
			$title = trim($row["title"]);
			dibi::insert("book_title", array(
					"id_book"	=> $row["id"],
					"id_language"	=> $this->language,
					"title"			=> $title,
					'inserted'		=> new DibiVariable("now()", "sql"),
				))->execute();
			// WRITTEN BY
			dibi::insert("written_by", array(
					"id_author"		=> $row["writer"],
					"id_book"		=> $row["id"]
				))->execute();
			echo ".";
		}
		dibi::commit();
	}

	private function importDiscussion() {
		dibi::activate("source");

		dibi::activate("destination");
		dibi::begin();
		$topic = SimpleTableModel::createTableModel("discussable")->insert(array("table" => "topic", "column_id" => "id_topic", "column_name" => "name"));
		$opinion = SimpleTableModel::createTableModel("discussable")->insert(array("table" => "view_opinion", "column_id" => "id_opinion", "column_name" => "user_nick"));
		$author = SimpleTableModel::createTableModel("discussable")->insert(array("table" => "view_author", "column_id" => "id_author", "column_name" => "full_name"));
		
	}

	private function importLanguage() {
		dibi::activate("destination");
		$this->language	= SimpleTableModel::createTableModel("language")->insert(array("name" => "czech", "locale" => "cs"));
	}

	private function importOpinions() {
		dibi::activate("source");
		$rows = dibi::query("SELECT * FROM [reader_opinion]")->fetchAll();

		dibi::activate("destination");
		dibi::begin();

		foreach($rows AS $row) {
			dibi::insert("opinion", array(
				"id_language"	=> $this->language,
				"id_user"		=> $row["user"],
				"id_book"		=> $row["book"],
				"rating"		=> $row["rating"],
				"inserted"		=> $row["date"],
				"content"		=> $row["content"]
			))->execute();

			dibi::insert("in_shelf", array(
				"id_shelf"		=> $row["user"],
				"id_book"		=> $row["book"],
				"inserted"		=> $row["date"]
			))->execute();
			echo ".";
		}

		dibi::commit();
	}

	private function importRole() {
		dibi::activate("destination");
		$this->role = SimpleTableModel::createTableModel("role")->insert(array("name" => "common"));
	}

	private function importTables() {
		dibi::loadFile(dirname(__FILE__) . "/tables.sql");
	}

	private function importTagged() {
		dibi::activate("source");
		$rows = dibi::query("SELECT * FROM [reader_tagReference] WHERE [type] = 'book'")->fetchAll();

		dibi::activate("destination");

		foreach ($rows AS $row) {
			try {
				dibi::insert("tagged", array(
						"id_tag"	=> $row["tag"],
						"id_book"	=> $row["target"]
					))->execute();
			}
			catch(DibiDriverException $e) {}
			echo ".";
		}
	}

	private function importTags() {
		dibi::activate("source");
		$rows = dibi::query("SELECT * FROM [reader_tag]")->fetchAll();

		dibi::activate("destination");
		dibi::begin();

		foreach ($rows AS $row) {
			dibi::insert("tag", array(
					"id_language"	=> $this->language,
					"name"			=> trim($row["name"]),
					"id_tag"		=> $row["id"]
				))->execute();
			echo ".";
		}

		dibi::commit();
	}

	private function importUsers() {
		dibi::activate("source");
		$rows = dibi::query("SELECT * FROM [reader_user]")->fetchAll();

		dibi::activate("destination");
		dibi::begin();

		foreach($rows AS $row) {
			dibi::insert("user", array(
				"id_user"		=> $row["id"],
				"id_language"	=> $this->language,
				"id_role"		=> $this->role,
				"email"			=> String::lower($row["email"]),
				"password"		=> $row["password"],
				"id_user"		=> $row["id"],
				"nick"			=> utf8_decode($row["name"]),	// FIXME
				"inserted"		=> new DibiVariable("now()", "sql")
			))->execute();

			dibi::insert("shelf", array(
				"id_shelf"		=> $row["id"],			// HACK
				"id_user"		=> $row["id"],
				"type"			=> "read",
				"name"			=> "Mám přečteno",
				"inserted"		=> new DibiVariable("now()", "sql")
			))->execute();

			dibi::insert("shelf", array(
				"id_shelf"		=> $row["id"] + 2000,		// HACK
				"id_user"		=> $row["id"],
				"type"			=> "wanted",
				"name"			=> "Chci si přečíst",
				"inserted"		=> new DibiVariable("now()", "sql")
			))->execute();
			echo ".";
		}

		dibi::commit();
	}

	private function importWanted() {
		dibi::activate("source");
		$rows = dibi::query("SELECT * FROM [reader_readlist]")->fetchAll();

		dibi::activate("destination");
		foreach ($rows AS $row) {
			try {
				dibi::insert("in_shelf", array(
					"id_shelf"	=> $row["user"] + 2000,
					"id_book"	=> $row["book"]
				))->execute();
			}
			catch(DibiDriverException $e) {}
			echo ".";
		}
	}
}

$import = new Import();
$import->import();
die("\n\nDONE\n");