<?php
$fileStatus = fopen('soundboard_action.txt', 'r');
$serverStatus = fread($fileStatus, 4);
fclose($fileStatus);
if ($serverStatus == 'LOCK' && $_POST['txtUsername'] != 'fixitfixitfixit')
	echo '<br><h3>Soundboard is currently LOCKED.</h3>';
else if ($serverStatus == 'DEAD')
	echo '<br><h3>Soundboard is currently OFFLINE.</h3>';
ob_flush();
$username = "amani";
if ($_POST['txtUsername'] == $username or ($_COOKIE["txtUsername"] == $username && $_POST['txtUsername'] == "")) {


// ==================================================Actual Content=============================================
if ($_COOKIE["txtUsername"] != "amani") 
	setcookie ("txtUsername", "amani");

$pageFunctions = '<script type="text/javascript" language="javascript" src="soundboard.js"></script>';	



$mySelect = "<h2>Sojo Soundboard</h2>
	<button onclick=\"sidecatcontrol.location.href='sound_processor.php?action=play&param1=' + document.getElementById('listboxSounds').value\">Play It</button><br>
	<select name='listboxSounds' id='listboxSounds' size='44' ondblclick=\"sidecatcontrol.location.href='sound_processor.php?action=play&param1=' + document.getElementById('listboxSounds').value\">";
$dir = opendir(getcwd() . "/Sounds/");
while($file=readdir($dir)){
if ($file != "." && $file != ".."){
$mySelect .= "<option value=\"".$file."\">".$file."</option>";
}
} 
closedir($dir);
$mySelect .= "</select>";

ob_start();
echo "<head><link rel='shortcut icon' href='sndbo.ico'><link rel='stylesheet' type='text/css' href='sb-stylesheet.css' /><title>Sojo Soundboard</title>";
echo $pageFunctions . "</head>";
echo "<body onload=\"sortlist('listboxSounds')\">";
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