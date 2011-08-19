<?php

namespace TextCaptcha;
use \DibiConnection;

require_once __DIR__."/../other/Question.php";
require_once __DIR__."/IBackend.php";

/**
 * Provider of questions.
 * All questions are in database
 * @author Jan Drábek
 * @version 1.0
 * @license GNU-GPLv3
 */
class DatabaseBackend implements IBackend {
	
	private $connection;
	
	public function __construct(DibiConnection $connection) {
		$this->connection = $connection;
	}
	public function get($language) {
		$question = $this->connection->query("SELECT * FROM [captcha] WHERE [id_language] = %i ORDER BY RAND() LIMIT 1", $language)->fetch();
		if($question === FALSE) {
			throw new \Nette\InvalidArgumentException("Language [".$language."] has no questions.");
		}
		return Question::create($question->question,$question->answer);
	}
}