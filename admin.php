<?php
require("./include/config.php");
$admin = new admin;

switch($_GET["action"]) {
 default:
  $temp->header();
  $temp->menu();
  $temp->addAdvertForm();
  $temp->middle();
  $temp->adminUserSearchForm();
  $temp->footer();
 break;
 case "ban":
  $temp->header();
  $temp->menu();
  $admin->userBan($_GET[user]);
  $temp->adminUserSearch($_GET["order"],$_GET["page"],$_GET["name"]);
  $temp->middle();
  $temp->adminUserSearchForm();
  $temp->footer();  
 break;
 case $lng->search:
  $temp->header();
  $temp->menu();
  $temp->adminUserSearch($_GET["order"],$_GET["page"],$_GET["name"]);
  $temp->middle();
  $temp->adminUserSearchForm();
  $temp->footer();  
 break;
 case "newAdvert":
  $temp->header($lng->addAdvert);
  $temp->menu();
  $advert = new advertisement;
  $advert->create($_POST[content],$_POST[book],$_POST[endDate]);
  $temp->message($lng->advertCreated);
  $temp->middle();
  $temp->adminUserSearchForm();
  $temp->footer();    
 break;
 case "sendEmail":
  $temp->header();
  $temp->menu();
  $temp->addAdvertForm();
  $temp->middle();
  $temp->adminUserSearchForm();
  $temp->footer();  
 break;
 case "sendEmailForm":
  $temp->header();
  $temp->menu();
  $temp->adminSendEmailForm();
  $temp->middle();
  $temp->adminUserSearchForm();
  $temp->footer(); 
 break;
}
?>