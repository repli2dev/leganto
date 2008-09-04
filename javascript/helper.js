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