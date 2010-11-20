<?php
require_once dirname(__FILE__) . "/header.php";

Debug::enable(Debug::DEVELOPMENT);

Environment::loadConfig(APP_DIR . '/config.ini');

dibi::connect(Environment::getConfig("database"));

// ------------------------- AUTHORS -------------------------------------------

// Karel Čapek
$author = Leganto::authors()->getSelector()->find(5);
$help = $author->firstname;
$author->firstname = $author->lastname;
$author->lastname = $help;
$author->persist();

// Sergej Vasil'jevič Luk'janenko
$author = Leganto::authors()->getSelector()->find(619);
$author->firstname	= "Sergej Vasil'jevič";
$author->lastname	= "Luk'janenko";
$author->persist();

// Henryk Sienkiewicz
$author = Leganto::authors()->getSelector()->find(18);
$author->firstname = "Henryk";
$author->persist();

// Raymond Elias Feist
$author = Leganto::authors()->getSelector()->find(31);
$author->firstname = "Raymond Elias";
$author->persist();

// ------------------------- BOOKS ---------------------------------------------

// Kronika rodu Spiderwicků
$book = Leganto::books()->getSelector()->find(60);
$book->title = "Kronika rodu Spiderwicků";
$book->persist();

// Discussion when opinion is empty
// SELECT DISTINCT (follow) FROM  `reader_discussion` LEFT JOIN reader_opinion ON follow = reader_opinion.book WHERE TYPE =  'book' AND reader_opinion.id IS NULL