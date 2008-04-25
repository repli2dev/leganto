<?php
require ("./include/config.php");
$rss = new rss;
switch ($_GET["action"]) {
 case "user": $rss->user($_GET["user"]);
 break;
 case "favouriteGroup": $rss->favouriteGroup($_GET["user"]);
 break;
}
?>