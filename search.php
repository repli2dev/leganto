<?php
require("./include/config.php");

$temp->header("$lng->search - $_GET[searchWord]");
$temp->menu();
$temp->search($_GET["column"],$_GET["searchWord"],$_GET["order"],$_GET["page"]);
$temp->middle();
$temp->tagListTop();
$temp->footer()
?>