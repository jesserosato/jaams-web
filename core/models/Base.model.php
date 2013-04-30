<?php
namespace JAAMS\Core\Models;
<<<<<<< HEAD
=======
use \PDO as PDO;
use \PDOException as PDOException;
>>>>>>> 57359df9d6a55169e4262f7e80bae64210b2ba04

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
<<<<<<< HEAD
=======
		'driver'	=> \JAAMS\DB_DRIVER,
>>>>>>> 57359df9d6a55169e4262f7e80bae64210b2ba04
		'host'		=> \JAAMS\DB_HOST,
		'user'		=> \JAAMS\DB_USER,
		'password'	=> \JAAMS\DB_PASSWORD,
		'name'		=> \JAAMS\DB_NAME,
		'port'		=> \JAAMS\DB_PORT,
		'socket'	=> \JAAMS\DB_SOCKET
	);
<<<<<<< HEAD
=======
	protected $errmode		= PDO::ERRMODE_EXCEPTION;
>>>>>>> 57359df9d6a55169e4262f7e80bae64210b2ba04
	
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
<<<<<<< HEAD
		// Initialize mysqli connection
		try {
			$dbh = @new \mysqli(
				$this->db_info['host'],
				$this->db_info['user'], 
				$this->db_info['password'], 
				$this->db_info['name'],
				$this->db_info['port'],
				$this->db_info['socket']
			);
			if ($dbh->connect_errno) {
				$msg = "Failed to connect to MySQL: (" . $dbh->connect_errno . ") " . $dbh->connect_error;
			    throw new \Exception($msg);
			}
		} catch ( \Exception $e ) {
			throw new \Exception($e->getMessage());
		}

		$this->dbh = $dbh;
=======
		// Initialize db handle
		try {
			$this->init_dbh();
		} catch( PDOException $e ) {
			throw $e;
		}
>>>>>>> 57359df9d6a55169e4262f7e80bae64210b2ba04
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
<<<<<<< HEAD
=======
	
	
	/**
	 * init_dbh function.
	 * 
	 * @access public
	 * @param bool $reset (default: false)
	 * @return void
	 */
	public function init_dbh( $reset = false ) {
		if ( ! empty( $this->dbh ) && ! $reset )
			return;
		
		$dsn = $this->get_dsn();
		try {
			$this->dbh = new PDO($dsn, $this->db_info['user'], $this->db_info['password']);
			$this->dbh->setAttribute(PDO::ATTR_ERRMODE, $this->errmode);
		} catch ( PDOException $e ) {
			throw $e;
		}
	}
	
	// TODO: Add more drivers.
	/**
	 * get_dsn function.
	 * 
	 * @access public
	 * @return string
	 */
	public function get_dsn() {
		switch ( $this->db_info['driver'] ) {
			default :
				$dsn = "mysql:host=" . $this->db_info['host'] . ";";
				$dsn.= empty($this->db_info['port']) ? "" : $this->db_info['port'] . ";";
				$dsn.= "dbname=" . $this->db_info['name'] . ";";
		}
		return $dsn;
	}
>>>>>>> 57359df9d6a55169e4262f7e80bae64210b2ba04
}