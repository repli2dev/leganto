$(function(){
	// First hide radios (not done by css - it should work in non JS browsers)
	$('#search input[type=radio]').each(function() {
		$(this).css("display","none");
	});
	// Highlight the default item (the first one)
	$("#search label").first().attr("id","selected-search-option");
	// Triger selection by mouse
	$("#search label").click(function() {
		// Unset all others
		$('#search label').each(function() {
			$(this).attr("id", "");
		});
		// Set this one as current
		$(this).attr("id", "selected-search-option");
	});
});