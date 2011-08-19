<?php

namespace TextCaptcha;

require_once __DIR__."/../other/Question.php";
require_once __DIR__."/IBackend.php";

/**
 * Provider of questions.
 * All questions are in private array.
 * @author Jan Drábek
 * @version 1.0
 * @license GNU-GPLv3
 */
class ArrayBackend implements IBackend {
	private $data = array();
	
	public function __construct() {
		$data = array();
		// Czech
		$data["cs"][] = Question::create("Kolik má člověk nohou?","2","dvě");
		$data["cs"][] = Question::create("Kolik má pes nohou?","4","čtyři");
		$data["cs"][] = Question::create("Kolik má pavouk nohou?","osm","8");
		$data["cs"][] = Question::create("Jaká je barva letní oblohy?","modrá");
		$data["cs"][] = Question::create("Napište druhé číslo z 52, 98, 65, 32.","98");
		$data["cs"][] = Question::create("Napište část těla z preclík, voda, klávesnice, nos.","nos");
		$data["cs"][] = Question::create("Která číslice je poslední v čísle 87823","3","tři");
		$data["cs"][] = Question::create("Je-li dnes pondělí, co bylo včera za den?","neděle");
		$data["cs"][] = Question::create("Bylo-li předvčerejškem pondělí, co bude zítra za den?","čtvrtek");
		$data["cs"][] = Question::create("Napište abstraktní pojem z pomeranč, grep, válka, otroctví?","válka","otroctví");
		$data["cs"][] = Question::create("Napište kolik slov z hruška, lampa, banán je ovoce.","2","dva");
		$data["cs"][] = Question::create("Co mezi slova nepatří rajče, olivy, připínáček, chleba.","připínáček");
		
		// English
		$data["en"][] = Question::create("What is the color of sky?","blue");
		$data["en"][] = Question::create("Write tool from mice, pie, hammer?","hammer");
		
		$this->data = $data;
	}
	public function get($language) {
		if(!isSet($this->data[$language])) {
			throw new \Nette\InvalidArgumentException("Language [".$language."] not exists.");
		}
		$num = count($this->data[$language]);
		if($num == 0) {
			throw new \Nette\InvalidArgumentException("Language [".$language."] has no questions.");
		}
		return $this->data[$language][rand(0,$num-1)];
	}
}