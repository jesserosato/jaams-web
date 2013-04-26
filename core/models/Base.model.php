<?php
namespace JAAMS\Core\Models;

/**
 * Base Model class.
 * Handles data store interactions.
 */
class Base {
	// PROPERTIES
	// - PROTECTED
	protected $dbh;						// Database handle
	protected $controller;
	protected $errmode		= PDO::ERRMODE_EXCEPTION;
	// - PRIVATE
	private $db_info		= array(
		'driver'	=> 'mysql',
		'host'		=> DB_HOST,
		'user'		=> DB_USER,
		'password'	=> DB_PASSWORD,
		'name'		=> DB_NAME,
		'port'		=> DB_PORT,
		'socket'	=> DB_SOCKET
	);
	
	// METHODS
	// - PUBLIC
	/**
	 * CONSTRUCTOR
	 *
	 * @param	$controller		Mixed
	 * @param	$db_host		String
	 * @param	$db_user		String
	 * @param	$db_password	String
	 * @param	$db_name		String
	 * @param	$db_port		String
	 * @param	$db_socket		String
	 *
	 */
	public function __construct( $controller, $db_info ) {
		// Controller
		$this->controller	= $controller;
		foreach ( $db_info as $key => $val ) {
			$this->db_info[$key] = $val ? $val : $this->db_info[$key];
		}
		// Initialize database
		// $this->dbh = $this->get_dbh();
		// Check for solid connection.
		// if ( $this->dbh->connect_errno )
			// throw new \Exception('JAAMSModel was unable to instantiate a database connection');
	}
	
	/**
	 * Set the error mode for the pdo;
	 *
	 * @param	$errmode	Mixed	PHP PDO errmode constant
	 *
	 */
	public function set_errmode( $errmode ) {
		$this->errmode = $errmode;
		$this->dbh->setAttribute(PDO::ATTR_ERRMODE,$this->errmode);  
	}
	
	/**
	 * Establish and return a MYSQLI database connection.
	 *
	 * @param	$db_info	array
	 * @return	mysqli
	 */	
	public function get_dbh( ) {
	}
}