<?php
namespace JAAMS\Core\Models;

/**
 * Base Model class. Uses mysqli.
 * Handles data store interactions.
 */
class Base {
	// PROPERTIES
	// - PROTECTED
	protected $dbh;						// Database handle
	protected $_controller;
	protected $data			= array();
	// - PRIVATE
	protected $db_info		= array(
		'host'		=> \JAAMS\DB_HOST,
		'user'		=> \JAAMS\DB_USER,
		'password'	=> \JAAMS\DB_PASSWORD,
		'name'		=> \JAAMS\DB_NAME,
		'port'		=> \JAAMS\DB_PORT,
		'socket'	=> \JAAMS\DB_SOCKET
	);
	
	// METHODS
	// - PUBLIC
	/**
	 * CONSTRUCTOR
	 *
	 * @param	$controller		Mixed
	 * @param	$db_info		Array
	 *
	 */
	public function __construct( $controller, array $db_info = array() ) {
		// Controller
		$this->_controller	= $controller;
		$this->db_info = array_merge($this->db_info, $db_info);
			try {
				// Initialize mysqli connection
				$dbh = @new \mysqli(
					$this->db_info['host'],
					$this->db_info['user'], 
					$this->db_info['password'], 
					$this->db_info['name'],
					$this->db_info['port'],
					$this->db_info['socket']
				);
				if ($dbh->connect_errno) {
				    throw new \Exception("Failed to connect to MySQL: (" . $dbh->connect_errno . ") " . $dbh->connect_error);
				  }
			} catch( \Exception $e ) {
				if ( ! empty ( $GLOBALS['JAAMS']['DEBUGGER'] ) ) {
					$GLOBALS['JAAMS']['DEBUGGER']->debug_log($e->getMessage());
			}
		} 
		$this->dbh = $dbh;
	}
	
	
	/**
	 * __set function.
	 * 
	 * @access	public
	 * @param	string	$property
	 * @param	mixed	$value
	 * @return	void
	 */
	public function __set( $property, $value ) {
		// Try and call the set_$property method.
		$method = 'set_'.$property;
		if ( is_callable( $this, $method ) ) {
			$this->$method($value);
		}
		// Otherwise, use the class defaults to make sure the value being passed is the same
		// type as the default.
		$defaults = get_class_vars(get_class($this));
		// Make sure the new value is of the same type as the default value.
		if ( gettype( $value ) != gettype( $defaults[$property] ) )
			return;
		
		$this->$property = $value;
	}
}