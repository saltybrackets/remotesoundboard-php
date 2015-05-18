# Remote Soundboard #
Page to add and play sounds to a remote sound source.
Act as a sound source, and you'll be able to hear all sounds played on the selected channel.
Meant to be a simple "drag/drop" solution, there is no database required; just drop all files onto your web server, and you're ready to go!

### Features ###
- Channels allow for the same soundboard page to be used by different groups. No need to create a new page just so one group can have their own soundboard.
- Play any selected sound, and anyone with the page open and acting as a sound source will hear it.
- Play random sounds from the currently selected directory.
- Add/upload sounds to currently selected directory.
- Requests to play a sound are anonymous; good for plausible deniability in tricky HR situation in an office setting.
- Getting annoyed by too many people playing sounds? Lock the soundboard temporarily on the selected channel (also anonymous).
- Commands are simply query paramters to a PHP page, meaning automated processes may play sounds by simply hitting a specific URL. No need to access the actual soundboard page!

### Planned Features ###
- Directory creation (currently, directories must be created directly on web host).
- "Admin mode" to organize/delete files.
