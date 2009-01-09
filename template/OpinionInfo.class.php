<?php
/**
* @package readerTemplate
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://ctenar.cz
*/
/**
* Box s nazorem.
* @package readerTemplate
*/
class OpinionInfo extends Div {
	
	/**
	 * @param record
	 */
	public function __construct($opinion) {
		parent::__construct();
		$this->setClass("opinion");
		$info = new Div();
		$info->setClass("info");
		$this->setID("opinionInfo_".User::simpleName($opinion->userName));
		$author = new Span();
		$author->setClass("author");
		$author->addValue(new A(
			$opinion->userName,
			"user.php?user=".$opinion->userID
		));
		$info->addValue($author);
		unset($author);
		$rating = new Img("image/rating_".$opinion->rating.".png");
		$info->addValue($rating);
		unset($rating);
		$this->addValue($info);
		unset($info);
		$this->addValue(new String($opinion->content,TRUE));
	}
}
?>