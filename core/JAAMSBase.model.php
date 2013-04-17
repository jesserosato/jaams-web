<?php

/**
 * "Abstract" Base Model Class
 * Note that true abstraction is not supported before PHP5
 */
class JAAMSBase_Model {
	// PROPERTIES
	// - PROTECTED
	protected $db;
	protected $controller;
	
	// METHODS
	// - PUBLIC
	/**
	 * CONSTRUCTOR
	 *
	 * @param	$db_host		String
	 * @param	$db_user		String
	 * @param	$db_password	String
	 * @param	$db_name		String
	 * @param	$db_port		String
	 * @param	$db_socket		String
	 *
	 */
	public function __construct( 
		$controller,
		$db_host			= false, 
		$db_user			= false, 
		$db_password		= false, 
		$db_name			= false,
		$db_port			= false, 
		$db_socket			= false 
	) {
		// Controller
		$this->controller	= $controller;
		// Initialize database
		$this->db = $this->get_db_connection(
			$db_host, 
			$db_user,
			$db_password,
			$db_name
			$db_port,
			$db_socket
		);
		// Check for solid connection.
		if ( $this->db->connect_errno )
			throw new Exception('JAAMSModel was unable to instantiate a database connection');
	}
	
	/**
	 * Establish and return a MYSQLI database connection.
	 *
	 * @param	$db_host		String
	 * @param	$db_user		String
	 * @param	$db_password	String
	 * @param	$db_name		String
	 * @param	$db_port		String
	 * @param	$db_socket		String
	 *
	 * @return	mysqli
	 */	
	public function get_db_connection(
		$db_host			= false,
		$db_user			= false,
		$db_password		= false,
		$db_name			= false,
		$db_port			= false,
		$db_socket			= false
	) {
		// Database
		$db_host			= $db_host ? $db_host : JAAMS_DB_HOST;
		$db_user			= $db_host ? $db_host : JAAMS_DB_USER;
		$db_password		= $db_host ? $db_host : JAAMS_DB_PASSWORD;
		$db_name			= $db_host ? $db_host : JAAMS_DB_NAME;
		// Initialize connection
		return new mysqli($db_host, $db_user, $db_password, $db_name, $db_port, $db_socket);
		
	}
}