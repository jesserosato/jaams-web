<?php

/**
 * Super simple debug logger.
 *
 */
class JAAMSDebugger
{
	protected $file;
	protected $path;
	protected $msgs = array();
	
	function __construct($path)
	{
		if ( !preg_match( '/\.(txt|log)$/', $path ) )
			throw new Exception("JAAMSDebugger requires a .txt or .log filename.");
		
		$this->path = $path;
		
		// Check that file was opened
		if ( !( $this->file = fopen( $this->path, "a" ) ) )
			throw new Exception("Error opening file at " . $this->path . ".");
	}
	
	function __destruct()
	{
		// Write messages when object leaves scope.
		foreach( $this->msgs as $key => $msg ) {
			$msg = "[" . $key . "] " . $msg . "\n";
			if ( !fwrite( $this->file, $msg ) )
				throw new Exception("Error writing to file (" . $this->path . ").");
		}
		fclose($this->file);
	}
	
	public function debug_log( $string )
	{
		if ( is_array( $string ) )
			$string = print_r($string, true);
		
		if ( !is_string( $string ) )
			throw new Exception("Function JAAMSDebugger::debug_log() requires string or array parameter.");
		 
		$this->msgs[date('Y-m-d H:i')] = $string;
	}
}