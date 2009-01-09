//globalni promenne
var http_request = false;
function helper_onBlur_bookTitle(input){
		if(document.getElementById("suggest_bookTitle").innerHTML != 0 && input.name != "bookTitle"){

		} else {
			ajaxCall("helper.php?what=writerName&bookTitle=" + document.getElementById("bookTitle").value);
		}
}

function ajaxCall(adresa) {
    http_request = false;

    if (window.XMLHttpRequest) { // Mozilla, Safari,...
      http_request = new XMLHttpRequest();
    } else if (window.ActiveXObject) { // IE
      try {
        http_request = new ActiveXObject("Msxml2.XMLHTTP");
      } catch (e) {
        try {
          http_request = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e) {}
      }
    }
    
    if (!http_request) {
      alert('Sorry, no support');
      return false;
    }

    http_request.onreadystatechange = alertContents;
    http_request.open('GET', adresa, true);
    http_request.send(null);
    
  }
function alertContents() {
  var writerName;

    if (http_request.readyState == 4) {
      if (http_request.status == 200) { 
        	writerName = http_request.responseText;  	  
			var temp = writerName.split(" ");
			//secondName
			if(temp[0] != 0){
				document.getElementById("writerNameSecond").value = temp[0];
			}
			var help = '';
			for(i = 1; i<10;i++){
				if(temp[i]!=undefined){
					help = help + temp[i];
					x = i + 1;
					if(temp[x]!=undefined){
						help = help + ' ';
					}
				}
			}
			if(help != 0){
				document.getElementById("writerNameFirst").value = help;
			} 
      } else {
        alert('Cannot be proceeded.');
      }
    }
}

function empty( mixed_var ){
    	return ( mixed_var === "" || mixed_var === 0   || mixed_var === "0" || mixed_var === null  || mixed_var === false  ||  ( is_array(mixed_var) && mixed_var.length === 0 ) );
}
function onClick_fillin_title(new_title){
	document.getElementsByName("title")[0].value = new_title;
	document.getElementsByName("disContent")[0].focus();
	window.scrollBy(0,-300);	
}
function onClick_fillin_username(new_username){
	document.getElementsByName("userName")[0].value = new_username;
	document.getElementsByName("message")[0].focus();
	window.scrollBy(0,-300);
}
function onClick_fillin_parent(parent,old_title){
	document.getElementsByName("parent")[0].value = parent;
	document.getElementsByName("parent_title")[0].value = old_title;
	document.getElementsByName("title")[0].focus();
	//odjet na konec stranky, kde je formular
	window.scrollBy(0,document.body.scrollHeight);
}
function onClick_remove_parent(){
	document.getElementsByName("parent")[0].value = "";
	document.getElementsByName("parent_title")[0].value = "";
	document.getElementsByName("title")[0].focus();
}