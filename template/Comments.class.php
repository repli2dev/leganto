<?php
/**
* @package		readerTemplate
* @author		Jan Papousek
* @copyright 	Internetovy ctenarsky denik
* @link 		http://ctenari.cz
*/

/**
 * 				Tato trida se stara o zobrazovani komentaru
 * @package 	readerTemplate
 */
class Comments extends Div {
	
	/**
	 * 			Konstruktor
	 *
	 * @param 	int		ID komentovane polozky.
	 * @param 	string	Typ komentovane polozky.
	 */
	public function __construct($id, $type = "book") {
		parent::__construct();
		// Nadpis
		$header = new H(2,Lng::COMMENTS);
		$header->setID("comment");
		$this->addValue($header);
		unset($header);
		
		//Samotne komentare
		$res = Discussion::read($id,$type);
		$i = 0;
		while($comment = mysql_fetch_object($res)) {
			$i++;
			$this->addValue(new CommentInfo($comment,$type,$i));
		}
	}
}
?>