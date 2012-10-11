// Get location of this directory
var __currentDirectory = window.location.pathname;
__currentDirectory = __currentDirectory.substring(0, __currentDirectory.lastIndexOf('/'));

// Sort lists
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

// Case sensitive sort
function caseInsensitiveSort(a, b) 
{ 
	var ret = 0;
	a = a.toLowerCase(); b = b.toLowerCase();
	if(a > b) 
		ret = 1;
	if(a < b) 
		ret = -1; 
	return ret; 
}

// Read the soundboard_action.txt file	
function readFile()
{
	var sbActionURL = "http://" + self.location.hostname + __currentDirectory + "/soundboard_action.txt";
	
	return sendRequestPOST(sbActionURL, null);
}

// Send a POST request
function sendRequestPOST(sURL, sData)
{
	var oRequest = new XMLHttpRequest();	
	oRequest.open("POST",sURL,false);
	oRequest.setRequestHeader("User-Agent",navigator.userAgent);
	oRequest.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
	oRequest.setRequestHeader("If-Modified-Since","");
	oRequest.send(sData);
	
	return oRequest.responseText;
}