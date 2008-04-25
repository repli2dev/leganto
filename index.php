<?php
require("./include/config.php");

$temp->header($lng->onlineReaderBook);
$temp->menu();
$temp->introduction();
$temp->topBook();
$temp->newBook();
$temp->userListTop();
$temp->middle();
$temp->tagListTop();
$temp->commentLast();

$temp->footer();
?>
