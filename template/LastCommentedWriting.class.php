<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Naposledy komentovane knihy.
* @package readerTemplate
*/
class LastCommentedWriting extends Div {
	
	public function __construct() {
		parent::__construct();
		$this->setClass("column");
		$this->addValue(new H(2,Lng::WRITING_COMMENT_LAST));
		$res = WritingComment::lastCommentedWriting(10);
		$ul = new Ul();
		if(mysql_num_rows($res) > 0){
			while($writing = mysql_fetch_object($res)) {
				$ul->addLi(new A(
					$writing->title,
					"writing.php?action=readOne&amp;id=".$writing->writingID,
					$writing->title
				));
			}
			$this->addValue($ul);
			unset($ul);
		}
	}
}
?>