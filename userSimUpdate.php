<?php
require("./include/config.php");
$usSim = new userSim;
$usSim->updateAll();
echo "A je hotovo!";
?>