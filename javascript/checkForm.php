<?
require_once("./../class/eskymofw/Autoload.class.php");
Autoload::add("../class");
Autoload::add("../class/eskymofw");
Autoload::add("../class/eskymofw/html");
?>

function checkForm(form) {
	var impColumns = getImportantFormColumns(form.getAttribute('name'));
	for (var i in impColumns) {
		if (form.elements.namedItem(impColumns[i]).value == '') {	
			var columnLabel = document.getElementById(<? echo "'".Form::LABEL_ID_PREFIX."'"; ?> + form.elements.namedItem(impColumns[i]).name).innerHTML;
			alert(<? echo "'".Language::WITHOUT_IMPORTANT_FORM_COLUMN."'"; ?> + "'"+columnLabel+"'");
			return false;
		}
	}
	return true;
	
}
