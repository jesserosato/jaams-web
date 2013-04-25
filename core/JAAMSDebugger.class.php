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
	
	function __construct ( $path = false ) {	
		// Use the constant if no path is provided.	
		if ( empty( $path ) ) {
			if ( ! defined( 'JAAMS_DEBUGGER_LOG' ) )
				throw new Exception("JAAMSDebugger requires a .txt or .log filename.");
			else
				$path = JAAMS_DEBUGGER_LOG;
		}
		// Make sure the path is valid.
		if ( !preg_match( '/\.(txt|log)$/', $path ) )
			throw new Exception("JAAMSDebugger requires a .txt or .log filename.");
		
		$this->path = $path;
		
		// Check that file was opened
		if ( !( $this->file = fopen( $this->path, "a" ) ) )
			throw new Exception("Error opening file at " . $this->path . ".");
	}
	
	function __destruct() {
		fclose($this->file);
	}
	
	public function debug_log( $data ) {
		if ( is_array( $data ) )
			$data = print_r($data, true);
		
		if ( !is_string( $data ) )
			throw new Exception("Function JAAMSDebugger::debug_log() requires string or array parameter.");
		$timestamp = date('Y-m-d H:i');
		$this->msgs[$timestamp] = $data;
		$msg = "[" . $timestamp . "] " . $data . "\n";
		if ( !fwrite( $this->file, $msg ) )
			throw new Exception("Error writing to file (" . $this->path . ").");
		
	}
}