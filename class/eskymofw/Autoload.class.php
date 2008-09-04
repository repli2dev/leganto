<?php
/**
* @package eskymoFW
* @author Eskymaci
*/
/**
* Automaticky nahrava soubory s deklarovanymi tridami.
* @package eskymoFW
* @example doc_example/Autoload.class.phps
*/
class Autoload {

	/**
	* @var string Znak, kterym se oddeluji balicky pri volani trid - MOMENTALNE NEFUNGUJE!!!
	*/
	protected static $pattern = "_";

	/**
	* @var array_string Cesty k deklarovanym tridam.
	*/	
	protected static $directory = array();

	/**
	* @var string Koncovka nahravanych souboru.
	*/
	protected static $ending = ".class.php";


	/**
	* Prida cestu do pole cest Autoload::$directory.
	* @param string Cesta k deklarovanym tridam (nesmi zacinat ani koncit lomitkem, resp. teckou)
	* @return void
	*/
	public static function add($dir) {
		//TODO: Kontrola zdvojenych polozek
		self::$directory[] = "./" . $dir . "/";
		//pridani vcetne podadresaru na prvni urovni (vice souboru by mohlo zpusobit chybu prilis mnoha otevrenych souboru)
		$temp = explode("/",$dir);
		if(empty($temp[1])){
			$subdir = dir("./" . $dir . "/");
			while (false !== ($entry = $subdir->read())) {
				//kontrola typu
				if(filetype("./".$dir."/".$entry."") == "dir" && ($entry != "." || $entry != "..")){
					//kontrola pritomnosti souboru koncicich .class.php
					$subdir2 = dir("./".$dir."/".$entry."");
					while (false !== ($entry2 = $subdir2->read())) {
						if(strpos($entry2,self::$ending) !== false && ($entry2 != "." || $entry2 != "..")){
							self::add("./".$dir."/".$entry."");
						}
					}
					$subdir2->close();
				}
			}
			$subdir->close();
		}
	}

	/**
	* Metoda, ktera je volana pri hledani souboru, kde je trida deklarovana.
	* @param string Nazev tridy.
	* @return boolean
	*/
	public static function load($className) {
		//$path = str_replace(self::$pattern,"/",$className);
		$path = $className . self::$ending;
		foreach(self::$directory AS $dir) {
			if (file_exists($dir . $path)) {
				require_once($dir . $path);
				return TRUE;
			}
		}
		return FALSE;
	}
}
// Zaregistruje autoload
spl_autoload_register(array("Autoload","load"));