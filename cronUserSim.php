<?php
require_once("include/config.php");

MySQL::connect();

UserSim::updateAll();
?>