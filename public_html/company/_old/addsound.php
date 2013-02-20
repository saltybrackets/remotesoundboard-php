<?
//Create values to be plugged in
$strSound = basename($_FILES['fileUpload']['name']);
$strExtension = substr($strSound, -4);

echo "Attempting to upload " . $strSound . ".....<br>";

if ($strExtension == ".mp3" || $strExtension == ".wav")
{
	$fileHandle = fopen("/home/primitj4/public_html/Sojo/SB/Sounds/" . $strSound,'w') or die("<br> Oops... FAIL! File may be too large, or is not an MP3.<br><br>");

	//Upload file
	if (basename($_FILES['fileUpload']['name']) !== "") 
	{
		move_uploaded_file($_FILES['fileUpload']['tmp_name'], "Sounds/" . basename($_FILES['fileUpload']['name']));
		echo "<b>Sound added!</b>";
	}
	
} else {
	echo "Problem with upload: File too large, or is not an audio file (must be a wav or mp3.";
}?>
	
	<body onload="setTimeout('window.top.location = window.top.location.href',1500)">
	</body>