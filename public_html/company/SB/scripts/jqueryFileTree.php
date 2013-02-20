<?php
//
// jQuery File Tree PHP Connector
//
// Version 1.01
//
// Cory S.N. LaViska
// A Beautiful Site (http://abeautifulsite.net/)
// 24 March 2008
//
// History:
//
// 1.01 - updated to work with foreign characters in directory/file names (12 April 2008)
// 1.00 - released (24 March 2008)
//
// Output a list of files for jQuery File Tree
//

$_POST['dir'] = urldecode($_POST['dir']);

$currentList = null;

if( file_exists(getcwd() . '/../' . $_POST['dir']) 
 && strpos($_POST['dir'], 'sounds/') !== false // Prevent spoofing root
 && strpos($_POST['dir'], '..') !== true) {
	$files = scandir(getcwd() . '/../' . $_POST['dir']);
	$currentList = $files;
	natcasesort($files);
	if( count($files) > 2 ) { /* The 2 accounts for . and .. */
		echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
		// All dirs
		foreach( $files as $file ) {
			if( file_exists(getcwd() . '/../' . $_POST['dir'] . $file) && $file != '.' && $file != '..' && is_dir(getcwd() . '/../' . $_POST['dir'] . $file) ) {
				echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file) . "/\">" . htmlentities($file) . "</a></li>";
			}
		}
		
		// Add File/Dir
		echo "<li class=\"play_random\"><a href=\"#\" rel=\"!!!RND:" . substr(htmlentities($_POST['dir']), 7) . "\"><span style=\"color: aqua\"> RANDOM SOUND</span></a></li>";
		echo "<li class=\"add_file\"><a href=\"#\" rel=\"+++FIL:" . substr(htmlentities($_POST['dir']), 7) . "\"><span style=\"color: lightblue\"> ADD SOUND</span></a></li>";
		echo "<li class=\"add_dir\"><a href=\"#\" rel=\"+++DIR:" . substr(htmlentities($_POST['dir']), 7) . "\"><span style=\"color: lightblue\"> ADD FOLDER</span></a></li>";
		
		// All files
		foreach( $files as $file ) {
			if( file_exists(getcwd() . '/../' . $_POST['dir'] . $file) && $file != '.' && (strpos($file, '.mp3') > 0 || strpos($file, '.wav') > 0) && $file != '..' && !is_dir(getcwd() . '/../' . $_POST['dir'] . $file) ) {
				$ext = preg_replace('/^.*\./', '', $file);
				echo "<li class=\"file ext_$ext\"><a href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file) . "\">" . htmlentities($file) . "</a></li>";
			}
		}
		echo "</ul>";	
	}
} else 
{
	echo "ERROR LOADING FILE LIST";
}

// Make sure file list only contains wav's/mp3's.
function filterSoundFiles($var)
{
	return (strpos($var, '.mp3') || strpos($var, '.wav'));
}

try {
	$currentList = array_filter($currentList, 'filterSoundFiles');
	sort($currentList);
	echo '<script type="text/javascript">currentFileList = ' . json_encode($currentList) . ';</script>';
} catch (Exception $e) {
	// Error handling.
}
?>