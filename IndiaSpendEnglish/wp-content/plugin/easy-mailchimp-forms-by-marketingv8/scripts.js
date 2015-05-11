function emfw_change_style_preview(mypath) {
	var myelem = document.getElementById('styles');
	var styleid = myelem.options[myelem.selectedIndex].value;
	var prwdiv = document.getElementById('style_preview');
	var imgsrc = mypath + "styles/" + styleid + "/preview.png";
	prwdiv.innerHTML = '<img src="' + imgsrc + '"/>';
}