<?php
class moderator extends reader {
 
 public function __construct() {
  parent::__construct();
  $user = new user;
  $lng = new language;
  if ($this->owner->level < $user->levelModerator) die($lng->accessDenied);
 }

 public function addonChange($addonID,$content,$bookID) {
  $addon = new addon;
  $addon->change($addonID,$content,$bookID);
  unset($addon); 
 }

 public function addonCreate($bookID,$content) {
  $addon = new addon;
  $addon->create($bookID,$content);
  unset($addon);
 } 
 
 public function addonDestroy($addonID) {
  $addon = new addon;
  $addon->destroy($addonID);
  unset($addon);
 } 
 
 public function bookConnect($first,$second) {
  $book = new book;
  $book->connect($first,$second);
  unset($book);
 }
 
 public function bookEdit($bookID,$bookTitle,$writerName) {
  $book = new book;
  $book->changeAll($bookID,$bookTitle,$writerName);
  unset($book); 
 }
 
 public function tagConnect($startTag,$finishTag) {
  $tagRef =  new tagReference;
  $tagRef->connect($startTag,$finishTag);
  unset($tagRef);
 }
 
 public function tagEdit($id,$tagName) {
  $tag = new tag;
  $tag->change($id,$tagName);
  unset($tag);
 }
 
 public function writerEdit($id,$writerName) {
  $writer = new writer;
  $writer->change($id,$writerName);
  unset($writer);
 } 
}
?>