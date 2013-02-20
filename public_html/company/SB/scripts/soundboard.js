/*=========================================================================================
				Globals
-----------------------------------------------------------------------------------------*/		
	var soundFileURL;
	var interfaceReady = false;
	var ajaxListen = null;
	var currentFileList = null;

	
	
/*=========================================================================================
				Startup
-----------------------------------------------------------------------------------------*/
$(document).ready( function() {
	
	// Load the file tree
	window.loadFileTree();
	window.justLoaded = true;
	statusBar.set('Soundboard loaded');
	
	// Allow checking for focus on elements
	jQuery.extend(jQuery.expr[':'], {
		  focus: "a == document.activeElement"
		});
	
	
	
/*=========================================================================================
				Event Listeners
-----------------------------------------------------------------------------------------*/		

	/* ---------- Main Controls --------- */
		// Button: Lock
		$('#btnLock').click( function() {
			console.log("Locking soundboard");
			lockBoard();
			$('#txtLock').val('');
		});	
		
		// Button: Play
		$('#btnPlay').click( function() {
			console.log("Playing sound");
			queueSound(soundFileURL);
		});	
		
		// FileTree: Double-click
		$('#treeSounds').dblclick( function() {
			$('#btnPlay').click();
		});
		
		// Checkbox: Act as speaker.
		$('#chkSpeaker').click( function() {
			if ( $('#chkSpeaker').is(':checked') )
			{
				statusBar.set('Speaker Mode: <span style="color:lime">ON</span');
				sbListen();
			}
			else
			{
				statusBar.set('Speaker Mode: <span style="color:red">OFF</span');
				ajaxListen.abort();
			}
		});
		
		// Button: (X) Exit Generic Popup
		$('#exitPopup').click( function() {
			popup.hide('popup');
		});	
	
	
	/* ---------- File Upload --------- */			
		// Button: (X) Exit File Upload
		$('#exitSoundUpload').click( function() {
			popup.hide('popupSoundUpload');
			window.loadFileTree();
			$('#frameAddSound').attr('src', 'addsound.html');
		});	
	
	
	/* ---------- Make Directory --------- */
		// Button: Create
		$('#btnMakeDir').click( function () {
			popup.hide('popupMakeDir');
			popup.message('Creating folder: <span style="color:darkslategrey">' + currentDir + 
					'</span><span style="color:lime">' + $('#txtMakeDir').val() + '</span>...');
			popup.show('popup');
			makeDir( $('#txtMakeDir').val() );
		});
		
		// Button: (X) Exit Make Directory
		$('#exitMakeDir').click( function() {
			popup.hide('popupMakeDir');
			window.loadFileTree();
		});	
	
});
	


/*=========================================================================================
				Soundboard Listener Client
-----------------------------------------------------------------------------------------*/		
	
	/**
	 * Have the soundboard act as a speaker, and poll the server repeatedly to see if there
	 * is a sound to play. Loops infinitely as long as the speaker checkbox is checked.
	 */
	function sbListen()
    {
		console.log( 'Speaker mode: ' + $('#chkSpeaker').is(':checked') );
		if ( $('#chkSpeaker').is(':checked') )
		{
			// Open an AJAX call to the server's Long Poll PHP file
	        ajaxListen = $.get('scripts/sb_poll.php?cmd=listen&channel=' + $('#txtChannel').val(), function(data) 
	        {
	        	response = JSON.parse(data);
	        	statusBar.set('Server: ' + response.message);
	            
	        	// Hande server response
	        	switch (response.queue)
	        	{
	        		// Don't do anything. Nothing happened.
	        		case 'idle':
	    	    		if ( $('#chkSpeaker').is(':checked') )
	    	    			setTimeout('sbListen()', 1000);
	        			break;
	        		
	        		// A sound is queued. Play it.
	        		case 'play':
	        			playSound(response.target);
	        			clearSound();
	    	    		if ( $('#chkSpeaker').is(':checked') )
	    	    			setTimeout('sbListen()', 5000);
	        			break;
	        	}

	        });
		}
    }


	
/*=========================================================================================
				Functions / Objects
-----------------------------------------------------------------------------------------*/		

	/**
	 * Load/refresh the file tree.
	 */
	window.loadFileTree = function ()
	{
	    $('#treeSounds').fileTree({
	        root: 'sounds/', script: 'scripts/jqueryFileTree.php', expandSpeed: 100, collapseSpeed: 100, multiFolder: false
	    }, function(file) {
	    	clickFileTree(file);
		});
	};
	
	/**
	 * Performed every time the sounds list is click.
	 * 
	 * @param file	The currently selected item in the list.
	 */
	function clickFileTree(file)
	{	
		soundFileURL = file; // Global variable contains what is selected in the list.
		currentDir = file.substring(7);
		$('#frameAddSound').contents().find('#fileDir').val( currentDir ); // Update directory value of file upload form.
		console.log('Selection type: ' + soundFileURL.substring(0,7));
		
		// Change the status bar's default message based on what is selected in the list.
	    switch (soundFileURL.substring(0,7))
	    {
	    	case "!!!RND:":
	    		statusBar.idle = 'Selected: <span style="color:green">RANDOM SOUND</span>';
	    		break;	    		
	    	case "+++FIL:":
	    		statusBar.idle = 'Selected: <span style="color:green">ADD SOUND</span>';
	    		popup.show('popupSoundUpload');
	    		break;    		
	    	case "+++DIR:":
	    		statusBar.idle = 'Selected: <span style="color:green">ADD FOLDER</span>';
	    		popup.show('popupMakeDir');
	    		break;	    		    		
	    	default:
	    		statusBar.idle = 'Selected: <span style="color:green">' + soundFileURL.substring(7) + '</span>';
	    		break;
	    }
		statusBar.set(statusBar.idle);
	}	
	
	
	/**
	 * Controls the status bar.
	 */
	var statusBar = {
			
		idle: 'Status: Idle', // The default message in the status bar.
		timeout: 5000, // How long to show new messages before going back to the idle message.
		timer: null,
			
		/**
		 * Show a new message in the status bar for a limited time.
		 * @param status
		 */
		set: function(status)
		{
			$('#statusBar').html(status);
			if (statusBar.timer)
				clearTimeout(statusBar.timer);
			statusBar.timer = setTimeout('$("#statusBar").html(statusBar.idle)', statusBar.timeout);
		}
	};
	
	
	/**
	 * Show or hide 'popup' styled divs.
	 */
	var popup = {
			
		/**
		 * Show a div.
		 * 
		 * @param divID		The id of the ID to show/hide
		 */
		show: function(divID)
		{
			$('#'+divID).css('display', 'block');
			$('#'+divID).css('visibility', 'visible');
		},

		/**
		 * Hide a div.
		 * 
		 * @param divID		The id of the ID to show/hide
		 */
		hide: function(divID)
		{
			$('#'+divID).css('display', 'none');
			$('#'+divID).css('visibility', 'hidden');
		},
		
		/**
		 * For generic popups, change the displayed message.
		 * 
		 * @param lblID	The id of the label that will contain the message.
		 * @param text		The text to display.
		 */
		message: function(text)
		{
			$('#popupMessage').html(text);
		}
	};
	
	
	/**
	 * Run any commands upon Flash's external interface becoming available
	 * for JavaScript interaction.
	 */
	function externalInterface()
	{
		console.log('External Interface ready.');
		interfaceReady = true;
	}
	
	
	/**
	 * Tell the server to lock the soundboard temporarily.
	 * 
	 * @param time	Time in minutes to lock the soundboard.
	 */
	function lockBoard(time)
	{
		$.get('scripts/sb_poll.php?cmd=lock&channel=' + $('#txtChannel').val() + '&duration=' + ($('#txtLock').val() * 60), function(data) {
			response = JSON.parse(data);
			console.log('Lock attempted, returned: ' + response.message);
			statusBar.set('Server: ' + response.message);
		});
	}
	
	
	/**
	 * Tell the server to queue up another sound to play.
	 * @param file
	 */
	function queueSound(file)
	{
	    // Check to see if the select object is actually a file.
		// If not, do something else
		// (ie: Queue a random file sound file, add directory, or add a file)
		switch (file.substring(0,7))
	    {
	    	// Queue a random file in the current directory.
	    	case "!!!RND:":
	    		file = getRandomFile(file);
	    		break;
	    	
	    	// Must be a regular file. Queue it for play.
	    	default:
	    		break;
	    }
		
		// Convert path/file to URI and query the server to queue the sound.
		file = encodeURIComponent(file); 
		$.get('scripts/sb_poll.php?cmd=play&channel=' + $('#txtChannel').val() + '&target=' + file, function(data) {
			response = JSON.parse(data);
			statusBar.set('Server: ' + response.message);
			console.log('Sent sound, returned: ' + response.message);
		});
	}
	
	
	/**
	 * In Speaker mode, after a sound is played, tell server to go idle again.
	 */
	function clearSound()
	{
		$.get('scripts/sb_poll.php?cmd=clear&channel=' + $('#txtChannel').val(), function(data) {
			response = JSON.parse(data);
			console.log('Clear attempted, returned: ' + response.message);
		});
	}	

	
	/**
	 * Tell Flash sound player object to interrupt any sound currently
	 * being played, then play another.
	 * 
	 * Must be a WAV or MP3 file.
	 * 
	 * @param file	URL of the sound file to be played.
	 */	
	function playSound(file)
	{
		if (typeof file !== "undefined") {
			console.log('Playing sound: ' + file);
			if (interfaceReady)
				document.getElementById("SoundPlayer").playSound(file);
		}
	}
	
	
	/**
	 * Pick a random file for the currently loaded file list.
	 * 
	 * @param dir	The path where all the files in the list reside.
	 * @returns		Path + randomly chosen file.
	 */
	function getRandomFile(dir)
	{
		dir = 'sounds/' + dir.substring(7);
		var randomFile = currentFileList[Math.floor(Math.random() * currentFileList.length)];
		randomFile = dir + randomFile;
		
		return randomFile;
	}
	
	function createDir(dir)
	{
		ajaxMakeDir = $.get('scripts/sb_poll.php?cmd=listen&channel=' + $('#txtChannel').val(), function(data) 
        {
        	
        });
	}
	