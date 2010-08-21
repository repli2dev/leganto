<?php
require_once dirname(__FILE__) . "/header.php";

Debug::enable(Debug::DEVELOPMENT);

Environment::loadConfig(APP_DIR . '/config.ini');

dibi::connect(Environment::getConfig("database"));

dibi::query("TRUNCATE TABLE [edition]");

// Ziskam knihy
$processed	= dibi::query("SELECT DISTINCT(`id_book_title`) AS `id` FROM `edition`")->fetchPairs("id","id");
$books          = Leganto::books()->fetchAndCreateAll(Leganto::books()->getSelector()->findAll());
// Vytvorim vyhledavac obrazku
$imageFinder    = new EditionImageFinder();
// Vytvorim uloziste obrazku
$storage        = new EditionImageStorage();
// Vytvorim Google Books finder urcite jazykove verze
$googleFinder   = new GoogleBooksBookFinder("cs");

foreach ($books AS $book) {
	echo "GET [".$book->getId()."] ".$book->title."\n";
	// Ziskam data od Googlu
	try {
		$info = $googleFinder->get($book);

		// Na zaklade dat od Googlu vytvorim k dane knize edice
		$editions       = Leganto::editions()->getInserter()->insertByGoogleBooksInfo($book, $info);
		// Pro kazdou edici
		foreach($editions AS $edition) {
			// Ziskam obrazky (teoreticky je jich vice, i kdyz me to ted nezajima)
			try {
				$images = $imageFinder->get($edition);
				// Pokud nejsou obrazky prazdne, ulozim prvni z nich
				if (!empty($images)) {
					$storage->store($edition, new File(ExtraArray::firstValue($images)));
				}
			}
			catch(Exception $e) {}
		}
	}
	catch(Exception $e) {
		error_log($e->getMessage() . "\n" . $e->getTraceAsString());
		continue;
	}
}
