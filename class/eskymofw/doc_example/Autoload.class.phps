<?php
require_once("Autoload.class.php"); // Nacte tridu Autoload.

Autoload::add("class"); // Prida adresar ./class/
Autoload::add("zkouska"); // Prida adresar ./zkouska/

$zk = new Zkouska; // Vytvori instanci tridy Zkouska ---> zavola se autoload.
?>
