function sortlist() {
	var lb = document.getElementById('listboxSounds');
	arrTexts = new Array();
	
	for(var i=0; i<lb.length; i++)  {
		arrTexts[i] = lb.options[i].text;
	}
	
	arrTexts.sort(caseInsensitiveSort);
	
	for(var i=0; i<lb.length; i++)  {
		lb.options[i].text = arrTexts[i];
		lb.options[i].value = arrTexts[i];
	}
}
	
function caseInsensitiveSort(a, b) 
{ 
	var ret = 0;
	a = a.toLowerCase();b = b.toLowerCase();
	if(a > b) 
	ret = 1;
	if(a < b) 
	ret = -1; 
	return ret; 
}