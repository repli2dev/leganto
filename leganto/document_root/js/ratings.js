$(function(){
	// Firstly set a to <p> ID to be accesible only one select (with rating)
	$("#frmaddOpinionForm-rating").parent().attr("id","ratings");
	// Create target element for onHover titles
	$caption = $("<span class=\"text-rating\">&nbsp;</span>");

	$("#ratings").stars({
		inputType: "select",
		captionEl: $caption // point to our newly created element
	});

	// Make it available in DOM tree
	$caption.appendTo("#ratings");
	$("#ratings").append("&nbsp;");
});