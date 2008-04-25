<?php
require("./include/config.php");

$user = new user;
$tag = new tag;
$book = new book;
$opinion = new opinion;
$recommend = new recommend;
$tagReference = new tagReference;
$writer = new writer;

$user->install();
$tag->install();
$book->install();
$opinion->install();
$recommend->install();
$tagReference->install();
$writer->install();
?>