<?
$action = $_GET['action'];
$param1 = $_GET['param1'];

if ($action == 'play')
{
	$fileAction = fopen('soundboard_action.txt', 'w');
	fwrite($fileAction, "PLAY " . $param1);
	fclose($fileAction);
}

if ($action == 'txts')
{
	$fileAction = fopen('soundboard_action.txt', 'w');
	stripslashes($action);
	fwrite($fileAction, "TXTS " . $param1);
	fclose($fileAction);
}

if ($action == 'idle')
{
	$fileAction = fopen('soundboard_action.txt', 'w');
	fwrite($fileAction, "IDLE");
	fclose($fileAction);	
}

if ($action == 'lock')
{
	$fileAction = fopen('soundboard_action.txt', 'w');
	fwrite($fileAction, "LOCK");
	fclose($fileAction);	
}

if ($action == 'okay')
{
	$fileAction = fopen('soundboard_action.txt', 'w');
	fwrite($fileAction, "OKAY");
	fclose($fileAction);	
}

?>