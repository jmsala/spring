<?php
namespace Spring;

/**
 * Class SpLog is used to log messages.
 * Can be logged to files, databases, console, etc...
 * 
 * Exceptions:
 * 410 - Log file cannot be opened
 * 411 - Log file is not writable
 * 412 - Log message could not be written to log file
 * 413 - Log file could not be closed after writing
 */
class SpLog
{
	/**
	 * Saves a message to log file
	 * The datetime of the event is recorded automatically.
	 * @param string Message
	 * @throws SpException
	 * @return void
	 */
	static public function logToFile($message)
	{
		$format_message = "[" . date('Y/m/d h:i:s') . "] {$message}\n";
		// Let's make sure the file exists and is writable first.
		if (is_writable(LOG_FILE)) {
			// Create pointer in append mode (end of file)
		    if (!$handle = fopen(LOG_FILE, 'a')) {
		         throw new SpException("Cannot open file ({LOG_FILE})", 410);
		    }
		
		    // Write content to file.
		    if (fwrite($handle, $format_message) === FALSE) {
		        throw new SpException("Cannot write to file ({LOG_FILE})", 412);
		    }
		
		    if (fclose($handle) === false) {
		    	throw new SpException("Cannot close file ({LOG_FILE})", 413);
		    }
		
		} else {
		    throw new SpException("The file {LOG_FILE} is not writable", 411);
		}
	}
	
	static public function logToDb($message)
	{
		
	}
	
}
?>
