<!-- ---------------------------------------------------------- HTML ---------------------------------------------------------------------- -->

<html>
<head><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<!-- ----------CSS STYLESHEETS---------- -->	
		<!-- Soundboard CSS -->
		<link rel=stylesheet href="../css/soundboard.css" type="text/css" />

</head>
<body style='
	background: #888; 
	font-family: "Arial Black", Arial, sans-serif;
	font-weight: 900;
	overflow: hidden; 
	text-align: center; 
	user-select: none;'>

<!-- ---------------------------------------------------------- PHP ----------------------------------------------------------------------- -->
<?
/*=========================================================================================
 				CONSTANTS/SETTINGS
-----------------------------------------------------------------------------------------*/
	$mkdir = $_GET['mkdir'];
	
	// Set the target for the new directory.
	$target = '../sounds/' . $mkdir . $filename;
	
	// Used to determine if file is okay to upload.
	$validDir = false;
	
	// Default response that will be returned to uploader.
	$response = "Script Failure";

	
	
/*=========================================================================================
				MAKE DIRECTORY
-----------------------------------------------------------------------------------------*/
	
	// Check if $mkdir is valid for directory creation.
	
	
	
	
?>