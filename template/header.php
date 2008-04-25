<?php echo '<?xml version="1.0" encoding="UTF-8"?>' ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs">
  <head>
    <meta name="verify-v1" content="I+aF0CApBuKUA32gJ2y/ejZ1ycbXQdmLME8UekxUBBM=" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="Jan Papoušek"/>    
    <link rel="stylesheet" type="text/css" href="<?php echo $this->dirCSS ?>main.css" />
	 <!--[if lt IE 7]>
	 <link rel="stylesheet" type="text/css" href="style/ie.css" />
	 <![endif]-->
    <title><?php echo "$this->webName - $title" ?></title>
	 <script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
    </script>
    <script type="text/javascript">
      _uacct = "UA-2881824-1";
      urchinTracker();
    </script>
<?php
if ($_GET[action] == "addForm" or $_GET[suggest] == "yes") {
?>
    <script src="js/bookTitleList.php" type="text/javascript"></script>
    <script type="text/javascript" src="js/AutoSuggestBox.js"></script>
    <script type="text/javascript" src="js/writerNameFirstList.php"></script>
    <script type="text/javascript" src="js/writerNameSecondList.php"></script>
    <script type="text/javascript" src="js/userNameList.php"></script>
	 <script language="JavaScript">
	 	function AutoSuggestDeactive() {
	 		book = document.getElementById('SuggestResultsBook');
	 		book.style.visibility = 'hidden';	 		
	 		writerFirst = document.getElementById('SuggestResultsWriterFirst');
	 		writerFirst.style.visibility = 'hidden';
	 		writerSecond = document.getElementById('SuggestResultsWriterSecond');
	 		writerSecond.style.visibility = 'hidden';
	 	}
	 	
		function GetResultsHelp(a,sSearch) {
			var i, arrResults = new Array(a.length), arrBits, objResult, iCount = 0;
			for (i=0; i < a.length; i++) {
				arrBits = a[i].split('=');

				if (arrBits.length > 1){
					if (arrBits[1].toLowerCase().indexOf(sSearch.toLowerCase()) != -1) {
						objResult = {};
						objResult.id = arrBits[0];
						objResult.text = arrBits[1];

						arrResults[iCount] = objResult;
						iCount++;
					}
				}
			}
			return arrResults;		
		}

		function GetResultsWriterFirst(sSearch) {		
			var writer = new writerNameFirstList();
			var writerHelp = GetResultsHelp(writer,sSearch);
			return writerHelp;
		}		
		
		function GetResultsWriterSecond(sSearch) {		
			var writer = new writerNameSecondList();
			var writerHelp = GetResultsHelp(writer,sSearch);
			return writerHelp;
		}						
		
		function GetResultsBook(sSearch) {		
			var book = new bookTitleList();
			var bookHelp = GetResultsHelp(book,sSearch);
			return bookHelp;
		}

		function GetResultsUserName(sSearch) {
			var user = new userNameList();
			var userHelp = GetResultsHelp(user,sSearch);
			return userHelp;
		}

	function HandleChoice(sID, sText, arrExtra) {
	}
   </script>
<?php
}
?>
  </head>

