<?php
$a = new A(new String("Zobrazeny text odkazu"),"odkaz.php","titulek");
$a->view(); // Vypise: <a href="odkaz.php" title="titulek">Zobrazeny text odkazu</a>

$a = new A(new String("Zobrazeny text odkazu"),"odkaz.php");
$a->view() // Vypise: <a href="odkaz.php" title="Zobrazeny text odkazu">Zobrazeny text odkazu</a>

?>
