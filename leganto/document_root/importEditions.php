<?php
require_once(dirname(__FILE__) . "/constants.php");
require_once LIBS_DIR . '/Nette/loader.php';

// Loader
$loader = new RobotLoader();
$loader->addDirectory(APP_DIR);
$loader->addDirectory(LIBS_DIR);
$loader->register();

Debug::enable(Debug::DEVELOPMENT);

Environment::loadConfig(APP_DIR . '/config.ini');

dibi::connect(Environment::getConfig("database"));

dibi::query("TRUNCATE TABLE [edition]");

// Ziskam knihu
$books           = Leganto::books()->fetchAndCreateAll(Leganto::books()->getSelector()->findAll()->applyLimit(20));
// Vytvorim vyhledavac obrazku
$imageFinder    = new EditionImageFinder();
// Vytvorim uloziste obrazku
$storage        = new EditionImageStorage();
// Vytvorim Google Books finder urcite jazykove verze
$googleFinder   = new GoogleBooksBookFinder("cs");

foreach ($books AS $book) {
	echo "GET ".$book->title."\n";
	// Ziskam data od Googlu
	try {
		$info = $googleFinder->get($book);

		// Na zaklade dat od Googlu vytvorim k dane knize edice
		$editions       = Leganto::editions()->getInserter()->insertByGoogleBooksInfo($book, $info);
		// Pro kazdou edici
		foreach($editions AS $edition) {
			// Ziskam obrazky (teoreticky je jich vice, i kdyz me to ted nezajima)
			$images = $imageFinder->get($edition);
			// Pokud nejsou obrazky prazdne, ulozim prvni z nich
			if (!empty($images)) {
				$storage->store($edition, new File(ExtraArray::firstValue($images)));
			}
		}
	}
	catch(Exception $e) {
		continue;
	}
}