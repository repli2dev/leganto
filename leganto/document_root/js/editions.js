function transformISBN(selector, max) {
	selector.live('paste',function(e){
		selector.attr("maxlength",100);
		setTimeout(function() {
			var original = selector.val();
			original = original.replace(/-/g, '').substr(0,max);
			selector.val(original);
			selector.attr("maxlength",max);
		}, 100);
	});
}
$(document).ready(function() {
	transformISBN($("#frmaddEditionForm-isbn10"),10);
	transformISBN($("#frmaddEditionForm-isbn13"),13);
	transformISBN($("#frmeditEditionForm-isbn10"),10);
	transformISBN($("#frmeditEditionForm-isbn13"),13);
});