<?php
require("./include/config.php");

$user = new user;
$user->owner->id = 1;

$user->getSimilar();
?>