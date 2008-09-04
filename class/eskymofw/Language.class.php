<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Trida slouzici jako nositel textu, ktery se vypisuje na obrazovku.
*/
class Language {

	const ERROR = "Chyba";
	
	const NO_DB_SERVER = "Chyba v pripojeni k databazovemu serveru.";
	
	const NO_DATABASE = "Chyba v pripojeni k databazi.";
	
	const NO_FIELDSET = "Nebyl nalezen fieldset, do ktereho by se pridavaly prvky formulare.";
	
	const WRONG_CHARSET = "Chyba v nastaveni porovnavani.";

	const WITHOUT_IMPORTANT_COLUMN = "Nebyly vyplněny všechny nutné položky pro vytvoření záznamu v databázi.";
	
	const WITHOUT_IMPORTANT_FORM_COLUMN = "Nebylo vyplněno pole ";
	
	const WRONG_NUMBER_OF_TABLE_COLUMNS = "Byl zadán špatný počet sloupců tabulky.";
}
?>