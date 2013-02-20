<?
/**
 * Initiate long polling process server-side until something new
 * is ready in the command queue. Then fires off that command and
 * resumes long polling.
 * 
 */

/*=========================================================================================
 					Declarations
-----------------------------------------------------------------------------------------*/

	// How often to poll, in microseconds (1,000,000 μs equals 1 s)
	define('MESSAGE_POLL_MICROSECONDS', 500000);
	
	// How long to keep the Long Poll open, in seconds
	define('MESSAGE_TIMEOUT_SECONDS', 30);
	
	// Timeout padding in seconds, to avoid a premature timeout in case the last call in the loop is taking a while
	define('MESSAGE_TIMEOUT_SECONDS_BUFFER', 5);
	
	// Default JSON data for $sbQueue
	define('SBQUEUE_DEFAULTS', '
			{
			   "delay" : 500,
			   "lastPlay" : 0,
			   "lock" : {
			      "duration" : 0,
			      "password" : "",
			      "time" : 0
			   },
			   "queue" : "idle",
			   "target" : "",
			   "message" : "Awaiting command."			
			}
		');

	// Default duration and max duration for soundboard timelock.
	define('SB_LOCK_MIN', 60);
	define('SB_LOCK_MAX', 900);
	
	// Date/timestamp, in case we need them.
	$date = new DateTime();
	$timestamp = $date->format('U');
	
	// Soundboard queue JSON
	$sbFile = '../channels/sbQueue_' . $_GET['channel'] . '.json';
	$sbQueue = json_decode(SBQUEUE_DEFAULTS);

	
	
	// Close the session prematurely to avoid usleep() from locking other requests
	session_write_close();
	
	// Automatically die after timeout (plus buffer)
	set_time_limit(MESSAGE_TIMEOUT_SECONDS+MESSAGE_TIMEOUT_SECONDS_BUFFER);
	

	

/*=========================================================================================
 					Command Selection
-----------------------------------------------------------------------------------------*/

	// Check for channel file and parse its JSON into array.
	if (file_exists($sbFile))
	{
		$sbQueue = json_decode( file_get_contents($sbFile) );
	}
	else
	{
		// If file doesn't exist, use defaults.
		$sbQueue = json_decode(SBQUEUE_DEFAULTS);
	}	
	
	
	switch (strtolower($_GET['cmd']))
	{
		// Ask to server to play a sound.
		case 'play':
			if (!( checkLock() ))
			{
				$sbQueue->queue = 'play';
				$sbQueue->target = $_GET['target'];
				$sbQueue->message = '<span style="color:lime">Playing ' . substr($_GET['target'], 7) . '</span>';
				$sbQueue->lastPlay = $timestamp;
			}
			echo json_encode($sbQueue);
			break;
		
		// Sound played, go back to idle.
		case 'clear':
			if (!( checkLock() ))
			{
				usleep(3000000);
				
				$sbQueue->queue = 'idle';
				$sbQueue->target = '';
				$sbQueue->message = 'Idle.';
			}
			echo json_encode($sbQueue);
			break;
			
		case 'lock':
			// Already a lock in place?
			checkLock();

			// Fix timelock value if necessary
			$timelock = $_GET['duration'];
			if ($timelock > SB_LOCK_MAX) $timelock = SB_LOCK_MAX;
			if ($timelock < SB_LOCK_MIN) $timelock = SB_LOCK_MIN;
			
			// Deny lock if there's already a longer timelock in place
			if ($timelock < $sbQueue->lock->duration) 
			{
				echo '{ "message":' . json_encode('<span style="color:red">Denied. Lock of longer duration (' . ($sbQueue->lock->duration / 60) . ' min) already in place.</span>') . '}';
				break;
			}
			
			// No problems, set the timelock
			$sbQueue->queue = 'idle';
			$sbQueue->target = '';
			$sbQueue->lock->time = $timestamp;
			
			$sbQueue->lock->duration = $timelock;
			$sbQueue->message = '<span style="color:red">Soundboard locked for ' . ($sbQueue->lock->duration / 60) . ' min.</span>';

			// Send response
			echo '{ "message":' . json_encode('<span style="color:lime">Set lock for ' . ($sbQueue->lock->duration / 60) . ' min.</span>') . '}';
			break;
			
		case "unlock":
			// TODO
			break;
			
		case "setdelay":
			// TODO
			break;
			
		// Default. Acting as sound server, wait for sbCommand.json to be created.
		default:
			listen();
			break;				
	}
	
	// Save queue file if there were changes.
	file_put_contents($sbFile, json_encode($sbQueue));
	


/*=========================================================================================
 					Long Polling
-----------------------------------------------------------------------------------------*/

function listen()
{
	global $date, $timestamp;
	global $sbQueue, $sbFile;
	
	// Counter to manually keep track of time elapsed (PHP's set_time_limit() is unrealiable while sleeping)
	$counter = MESSAGE_TIMEOUT_SECONDS;
	
	// Poll for messages and hang if nothing is found, until the timeout is exhausted
	while($counter > 0)
	{	
		// Check for channel file and parse its JSON into array.
		if (file_exists($sbFile))
		{
			$sbQueue = json_decode( file_get_contents($sbFile) );
		}
		else
		{
			// If file doesn't exist, use defaults and make a new channel file.
			$sbQueue = json_decode(SBQUEUE_DEFAULTS);
			file_put_contents($sbFile, json_encode($sbQueue));
		}
		
		
		// Keep looping if no new commands (idle).
		if ($sbQueue->queue == 'idle')
		{
			// Sleep for the specified time, after which the loop runs again
			usleep(MESSAGE_POLL_MICROSECONDS);
			
			// Decrement seconds from counter (the interval was set in μs, see above)
			$counter -= MESSAGE_POLL_MICROSECONDS / 1000000;
		}
		
		// Found a command, break out of the loop
		else
		{
			break;
		}
	}
	

	// Either we're still idle and timed out, or we have a command to issue. Handle it.
	
	// Check if there's a lock in place, unlock if necessary.
	checkLock();
	
	echo json_encode($sbQueue);
}

/*=========================================================================================
 					Functions
-----------------------------------------------------------------------------------------*/

function checkLock()
{
	global $date, $timestamp;
	global $sbQueue, $sbFile;
	
	if ($sbQueue->lock->duration > 0)
	{
		// Check if soundboard should remain locked.
		if ($timestamp < $sbQueue->lock->time + $sbQueue->lock->duration)
		{
			$sbQueue->message = '<span style="color:red">Soundboard locked for ' . $sbQueue->lock->duration . ' seconds.</span>';
			return true; // Soundboard is locked. Try again later.
		}
		
		// Soundboard lock has expired so unset it.
		else
		{
			$sbQueue->queue = 'idle';
			$sbQueue->lock->duration = 0;
			$sbQueue->lock->time = 0;
		}
	}
	
	// All checks show soundboard is unlocked.
	return false;
}