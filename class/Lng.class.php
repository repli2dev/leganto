<?php
/**
* @package reader
* @author Jan Papousek
* @copyright Jan Papousek 2007
* @link http://papi.chytry.cz
*/

/**
* Trida urcena pro uchovavani textu zobrazovaneho na obrazovce.
* @package reader
*/
class Lng {
 const ID = "ID";
 const ABOUT = "O projektu";
 const ACCESS = "Přístup";
 const ACCESS_DENIED = "Neoprávněný přístup";
 const ADD_ADDON = "Přidat doplněk ke knize";
 const ADD_ADVERT = "Přidat reklamu";
 const ADD_BOOK = "Přidat knihu";
 const ADD_COMMENT = "Zapojit se do diskuse";
 const ADD_DISCUSS = "Zapojit se do diskuse";
 const ADD_TAG = "Přidat klíčové slovo";
 const ADD_THIS_BOOK = "Mám přečteno";
 const ADD_TOPIC = "Vytvořit téma";
 const ADMIN = "Admin";
 const ALL = "Vše";
 const ALL_USER_BOOKS = "Všechny knihy";
 const BACKWARD = "Dozadu";
 const BOOK = "Kniha";
 const BOOK_BY_FAVOURITE = "Co čtou oblíbení uživatelé";
 const BOOK_COUNT = "Počet knih";
 const BOOK_EDIT = "Editovat knihu";
 const BOOK_TITLE = "Název knihy";
 const BOOK_WAS_READ = "Knihu četli";
 const CARMA = "Karma";
 const CHANGE = "Změnit";
 const CHANGE_ICO = "Změnit ikonku";
 const CHANGE_OPINION = "Změnit názor";
 const CHANGE_USER_INFO = "Změnit osobní údaje";
 const CHANGE_TOPIC = "Změnit téma";
 const CHANGE_WIKI = "Změnit informace o knize";
 const COMMENT_LAST = "Naposledy komentované knihy";
 const COMMENT_WITHOUT_TEXT = "Nemůžete přidat prázdný příspěvek do diskuse!";
 const COMMENT = "Diskusní příspěvek";
 const COMMENTS = "Diskuse";
 const comNotReaded = "Nové příspěvky v diskusích";
 const DELETE = "Odstranit";
 const DISCUSSION = "Povídat si";
 const DISCUSSION_CONTENT = "Obsah příspěvku";
 const DISCUSSION_LIST = "Seznam diskusí";
 const DISCUSS_WITHOUT_TEXT = "Nemůžete přidat prázdný příspěvek!";
 const EMAIL = "E-mail";
 const FAVOURITE_DESTROY = "Odstranit z oblíbených";
 const FAVOURITE_MAKE = "Přidat do oblíbených";
 const FORWARD = "Dopředu";
 const ICO = "Ikonka";
 const IMG_TOO_BIG = "Obrázek je příliš velký.";
 const INTRODUCTION = "Úvod";
 const ISBN = "ISBN";
 const LAST_BOOK = "Naposledy přidané knihy";
 const LAST_DISCUSSION = "Aktuální diskuse";
 const LAST_DISCUSSION_DATE = "Poslední příspěvek";
 const LAST_LOGGED = "Naposledy přihlášen";
 const level = "Úroveň";
 const LOG_IN = "Přihlásit se";
 const LOG_OUT = "Odhlásit";
 const MANAGMENT = "Správa";
 const MESSAGE = "Soukromá zpráva";
 const MESSAGES = "Soukromé zprávy";
 const MESSAGE_TO_OWNER = "Nelze poslat zprávu sám sobě";
 const MODERATOR = "Moderátor";
 const NAME = "Jméno";
 const NUMBER_OF_DISCUSSION = "Počet příspěvků";
 const NUMBER_OF_DISCUSSION_IN_TOPIC = "Počet diskusí";
 const NO_USER_FOR_MESSAGE = "Zpráva nebyla poslána, protože adresát neexistuje";
 const OPINION = "Názor";
 const OPINION_EXISTS = "Tuto knihu již máte ve svém čtenářském deníku.";
 const ORDER = "Seřadit";
 const PASSWORD= "Heslo";
 const PASSWORD_CONTROL = "Kontrola hesla";
 const RATING = "Hodnocení";
 const RATING_1 = "Odpad";
 const RATING_2 = "Špatné";
 const RATING_3 = "Průměrné";
 const RATING_4 = "Celkem dobré";
 const RATING_5 = "Vynikající";
 const READ = "Přečteno";
 const READER_BOOK = "Čtenářský deník";
 const REGISTRATE = "Registrovat";
 const REGISTRATION = "Registrace";
 const REMEMBER_ME = "Zapamatuj si mě";
 const RSS = "rss";
 const SEARCH = "Hledat";
 const SEARCH_ITEM = "Hledaná položka";
 const SEARCH_VALUE = "Hledaná hodnota";
 const SEND_MESSAGE = "Poslat zprávu";
 const SIMILAR_BOOKS = "Podobné knihy";
 const SIMILAR_USERS = "Podobní uživatelé";
 const SUBJECT = "Předmět";
 const TAG = "Klíčové slovo";
 const TAG_EDIT = "Změnit klíčové slovo";
 const TAGGED = "Počet označených knih";
 const TAGS = "Klíčová slova";
 const THIS_SITE = "Čtenářský deník";
 const TOP_BOOK = "Nejoblíbenější knihy";
 const TOP_USER = "Nejoblíbenější uživatelé.";
 const TOPIC = "Téma";
 const TOPIC_LIST = "Seznam témat";
 const UP = "Nahoru";
 const USER = "Uživatel";
 const USER_DESCRIPTION = "Něco o mně";
 const USER_EXISTS = "Uživatel s tímto jménem už existuje.";
 const USER_FAVOURITE = "Oblíbení uživatelé";
 const USER_NAME = "Jméno uživatele";
 const USERS = "Uživatelé";
 const WIKI = "Informace o knize";
 const WIKI_ALLOW = "Schválit";
 const WIKI_NOT_ALLOWED = "Neschválené wiki";
 const WITHOUT_BOOK_TITLE = "Nebyl vyplněn název knihy!";
 const WITHOUT_PASSWORD_AGREEMENT = "Hesla se neshodují!";
 const WITHOUT_TAG = "Nebylo přidáno žádné klíčové slovo.";
 const WITHOUT_WRITER_NAME = "Nebyl vyplněn autor knihy.";
 const WRITER = "Spisovatel";
 const WRITER_EDIT = "Změnit jméno spisovatele";
 const WRITER_NAME_FIRST = "Spisovatel - Křestní jméno";
 const WRITER_NAME_SECOND = "Spisovatel - Příjmení";
 const WRONG_IMG_TYPE = "Ikonka musí být ve formátu JPEG";
 const WRONG_NAME = "Zadané jméno nebylo nalezeno";
 const WRONG_PASSWORD = "Špatné heslo";
 
 const TEXT_ADD_BOOK_FORM = "Pokud zadáte název knihy nebo jejího autora, můžete jej stejně jako celý Váš názor změnit po kliknutí na odkaz \"Změnit názor\" na stránce dané knihy.";
 const TEXT_FORMAT_TEXT = "
<div class=\"formatTextInfo\">
<p>Text je formátován pomocí <a href=\"http://texy.info\" title=\"Texy!\">Texy!</a>. To znamená, že můžete používat například následující značky:</p>	
<ul>
	<li>**Tučné označení**</li>
	<li>*Kurzíva*</li>
	<li>\"Text odkazu\":http://adresa.odkazu</li>
	<li>- položka seznamu</li>
</ul>
</div>
 ";
 const TEXT_WIKI_NOT_ALLOWED = "Na této stránce můžete schvalovat a zamítat informace o knihách.";
}
?>
