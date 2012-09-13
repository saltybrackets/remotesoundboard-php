<?php


$username = "amani";
if ($_POST['txtUsername'] == $username or ($_COOKIE["txtUsername"] == $username && $_POST['txtUsername'] == "")) {


// ==================================================Actual Content=============================================
if ($_COOKIE["txtUsername"] != "amani")
	setcookie ("txtUsername", "amani");

$sortFunction =	"<script language=\"JavaScript\" type=\"text/javascript\">
					function sortlist() {
					var lb = document.getElementById('listboxSounds');
					arrTexts = new Array();
					for(i=0; i<lb.length; i++)  {
					arrTexts[i] = lb.options[i].text;
					}

					arrTexts.sort(caseInsensitiveSort);

					for(i=0; i<lb.length; i++)  {
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
					
					function timedCount()
					{
					SoundboardAction = readFile();
					if (SoundboardAction.substring(0,4) == \"PLAY\") {
						SoundToPlay = SoundboardAction.substring(5);
						sidecatcontrol.location.href='http://www.primitiveconcept.com/Sojo/SB/sound_processor.php?action=idle';
						niftyplayer('niftyPlayer1').loadAndPlay('Sounds/' + SoundToPlay);
					}
					t=setTimeout(\"timedCount()\",500);
					}
					
					function readFile()
					{
					var oRequest = new XMLHttpRequest();
					var sURL = \"http://\"
							 + self.location.hostname
							 + \"/Sojo/SB/soundboard_action.txt\";

					oRequest.open(\"POST\",sURL,false);
					oRequest.setRequestHeader(\"User-Agent\",navigator.userAgent);
					oRequest.setRequestHeader(\"Cache-Control\", \"no-cache, must-revalidate\");
					oRequest.setRequestHeader(\"If-Modified-Since\",\"\");
					oRequest.send(null)
	
					return oRequest.responseText;
					}
					
				</script>
				<script type=\"text/javascript\" language=\"javascript\" src=\"niftyplayer.js\"></script>";



$mySelect = "<h2>Sojo Soundboard</h2>
	<button onclick=\"sidecatcontrol.location.href='sound_processor.php?action=play&param1=' + document.getElementById('listboxSounds').value\">Play It</button><br>
	<select name='listboxSounds' id='listboxSounds' size='44' ondblclick=\"sidecatcontrol.location.href='sound_processor.php?action=play&param1=' + document.getElementById('listboxSounds').value\">";
$dir = opendir("/home/cp25004/public_html/Sojo/SB/Sounds/");
while($file=readdir($dir)){
if ($file != "." && $file != ".."){
$mySelect .= "<option value=\"".$file."\">".$file."</option>";
}
}
closedir($dir);
$mySelect .= "</select>";

ob_start();
echo "<head><link rel='shortcut icon' href='sndbo.ico'><title>Sojo Soundboard</title>";
echo $sortFunction . "</head>";
echo "<body onload=\"sortlist('listboxSounds'); timedCount();\">";
echo $mySelect;
?>
<iframe src="addsound.html" frameborder="0" align="top" name="addsound" id="addsound" width=300 height=200></iframe>
<iframe src="about:blank" frameborder="0" name="sidecatcontrol" id="sidecatcontrol" width=1 height=1></iframe>

<div>
    <br><input type="text" title="Text-to-Speech" id="txtToSpeech" name="txtToSpeech">
    <input type="button" name="btnTxtToSpeech" value="Text-to-Speech" onclick="sidecatcontrol.location.href='sound_processor.php?action=txts&param1=' + document.getElementById('txtToSpeech').value">
</div>

<form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <p><label for="txtUsername">Anything else to do?</label>
    <br /><input type="text" title="Enter passcode" name="txtUsername" /></p>
    <p><input type="submit" name="Submit"  value="Open Sesame" /></p>
</form>

<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="165" height="38" id="niftyPlayer1" align="">
<param name=movie value="niftyplayer.swf">
<param name=quality value=high>
<param name=bgcolor value=#FFFFFF>
<embed src="niftyplayer.swf" quality=high bgcolor=#FFFFFF width="165" height="38" name="niftyPlayer1" align="" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">
</embed>
</object>



<?php // =============================================================================================================

// ========================================= LOCK THE BOARD ==========================================================
} else if ($_POST['txtUsername'] == 'stopallthedownloading') {
?>


<h2>Soundboard is now LOCKED.</h2>
<form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <p><label for="txtUsername">Anything else to do?</label>
    <br /><input type="text" title="Enter passcode" name="txtUsername" /></p>
    <p><input type="submit" name="Submit" value="Open Sesame" /></p>
</form>
<iframe src="sound_processor.php?action=lock" frameborder="0" name="sidecatcontrol" id="sidecatcontrol" width=1 height=1></iframe>


<?php // =============================================================================================================

// ========================================= UNLOCK THE BOARD ========================================================
} else if ($_POST['txtUsername'] == 'fixitfixitfixit') {
?>


<h2>Soundboard has been UNLOCKED.</h2>
<form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <p><label for="txtUsername">Anything else to do?</label>
    <br /><input type="text" title="Enter passcode" name="txtUsername" /></p>
    <p><input type="submit" name="Submit" value="Open Sesame" /></p>
</form>
<iframe src="sound_processor.php?action=okay" frameborder="0" name="sidecatcontrol" id="sidecatcontrol" width=1 height=1></iframe>



<?php // =============================================================================================================
} else {
?> 

<h1>Login</h1>

<form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <p><label for="txtUsername">Who goes there?</label>
    <br /><input type="text" title="Enter passcode" name="txtUsername" /></p>
    <p><input type="submit" name="Submit" value="Open Sesame" /></p>
</form>
<iframe src="about:blank" frameborder="0" name="sidecatcontrol" id="sidecatcontrol" width=1 height=1></iframe>

<?php
}
?>

<?php
$fileStatus = fopen('soundboard_action.txt', 'r');
$serverStatus = fread($fileStatus, 4);
fclose($fileStatus);
if ($serverStatus == 'LOCK' && $_POST['txtUsername'] != 'fixitfixitfixit')
	echo '<br><h3>Soundboard is currently LOCKED.</h3>';
else if ($serverStatus == 'DEAD')
	echo '<br><h3>Soundboard is currently OFFLINE.</h3>';
ob_flush();
?>