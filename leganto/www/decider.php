<?php
// Facebook connect is returned to this address (fix address set in fb app)
// to perform redirection (facebook have dificulties with localhost addresses)
var_dump($_GET);
die;
header("Location: ".$_GET["next"]."&session=".$_GET["session"]);
