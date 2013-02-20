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
	// Max size for wav/mp3 files (in megabytes).
	define('MAX_WAV_SIZE', 4); 
	define('MAX_MP3_SIZE', 8); 
	
	// Acceptable mime types for audio files.
	$validMimes = array(
		'audio/mp3', 'audio/mpg', 'audio/mpeg', 'audio/mpeg3', 'audio/x-mpeg-3', 
		'audio/wav', 'audio/x-wav', 'audio/wave', 'audio/x-pn-wav'
	);
	
	// Default response that will be returned to uploader.
	$response = "Script Failure";



/*=========================================================================================
 				FILE INFO
-----------------------------------------------------------------------------------------*/
	// Basic file info.
	$file = $_FILES['file'];
	$filename = basename($_FILES['file']['name']);
	$filesize = $_FILES['file']['size'] / 1048576; // In mB
	$file_tmp = $_FILES['file']['tmp_name'];
	$fileext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
	
	// Get mime type of file for added security
	$file_info = new finfo(FILEINFO_MIME_TYPE);
	$filetype = $file_info->file($file_tmp);
	
	// Used to determine if file is okay to upload.
	$validFile = false;

	// Set the target for the upload.
	$target = '../sounds/' . $_POST['dir'] . $filename;

/*=========================================================================================
				FILE VALIDATION
-----------------------------------------------------------------------------------------*/
	// Make sure files are valid audio, and under file size limit. 
	if ( in_array($filetype, $validMimes) )
	{
		switch ($fileext)
		{
			case 'mp3':
				if ($filesize <= MAX_MP3_SIZE) $validFile = true;
				else 
				{
					$validFile = false;
					$response = 'MP3 is too large. Must be under ' . MAX_MP3_SIZE . ' megabytes.';
				}
				break;			
			case 'wav':
				if ($filesize <= MAX_WAV_SIZE) $validFile = true;
				else 
				{
					$validFile = false;
					$response = 'WAV is too large. Must be under ' . MAX_WAV_SIZE . ' megabytes.';
				}
				break;		
			default:
				$response = 'Invalid file type. Must be a valid MP3 or WAV audio file.';
				$validFile = false;
				break;
		}
	}
	else 
	{
		$validFile = false;
		$response = "Not a valid audio file. File is mimetype: $filetype";
	}
	
	
	
/*=========================================================================================
				FILE UPLOAD
-----------------------------------------------------------------------------------------*/	
	if ($validFile)
	{
		try 
		{
			move_uploaded_file($file_tmp, $target);
			$response = "$filename was uploaded successfully.";
		}
		catch (Exception $e) 
		{
			$response = "Error uploading file: $e";
		}
	}
	// Report success/failure.
	echo $response;
	
?></body></html>