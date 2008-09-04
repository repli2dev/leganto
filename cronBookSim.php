<?php
require_once("include/config.php");

MySQL::connect();

BookSim::updateAll();
?>