<?php
namespace JAAMS\Core\Utilities;
/**
 * Super simple debug logger.
 *
 */
class Debugger
{
	protected $file;
	protected $path;
	protected $msgs = array();
	
	function __construct ( $path = false ) {	
		// Use the constant if no path is provided.	
		if ( empty( $path ) ) {
			if ( ! defined( 'DEBUG_LOG' ) )
				throw new Exception("Debugger requires a .txt or .log filename.");
			else
				$path = DEBUG_LOG;
		}
		// Make sure the path is valid.
		if ( !preg_match( '/\.(txt|log)$/', $path ) )
			throw new Exception("Debugger requires a .txt or .log filename.");
		
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
			throw new Exception("Function Debugger::debug_log() requires string or array parameter.");
		$timestamp = date('Y-m-d H:i');
		$this->msgs[$timestamp] = $data;
		$msg = "[" . $timestamp . "] " . $data . "\n";
		if ( !fwrite( $this->file, $msg ) )
			throw new Exception("Error writing to file (" . $this->path . ").");
		
	}
}