/**********************/
/*   David Grudl      */
/*   phpfashion.com   */
/*   (part after DG)  */
/**********************/

$(document).ready(function() {
//style="height: 20px; overflow: hidden;"
// Hide authors by default
$("div.authors-hide").each(function(){
	$(this).css("line-height","20px");
	$(this).css("height","40px");
	$(this).css("overflow","hidden");
});
$("div.authors-hide span a:nth-child(3)").each(function(){
	$(this).after("...");
});
/* DG */
$("div.authors-hide").each(function(){
	var a=
	$(this).children().outerHeight(),b=$(this).height(),c=$(this).width();
	a>b&&$(this).hover(function(){
		$(this).stop(true,true).animate({
			height:Math.min(900,a+20)+"px",
			width:c+"px"
			},250)
		},function(){
		$(this).stop(true,true).animate({
			height:b,
			width:c+"px"
			},250)
		})
	});
});