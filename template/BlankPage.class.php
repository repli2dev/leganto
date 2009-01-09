<?php
/**
* @package reader
* @author Jan Drabek
* @copyright Jan Drabek 2008
*/

/**
 * Tato trida slouzi k zobrazeni uplne prazdne stranky (bez jakehokoliv html atd, coz je dulezite pro ajaxove funkce
 *
 */
class BlankPage extends CommonPage{
	/**
	 * Nic nedela, prepisuje rodicovskou funkci
	 */
    public function __construct(){
		parent::__construct();
	}
	public function view(){
		//just nothing

	}
}
?>