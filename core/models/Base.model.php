<?php
namespace JAAMS\Core\Models;

use \PDO as PDO;
use \PDOException as PDOException;

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
		'driver'	=> \Application\DB_DRIVER,
		'host'		=> \Application\DB_HOST,
		'user'		=> \Application\DB_USER,
		'password'	=> \Application\DB_PASSWORD,
		'name'		=> \Application\DB_NAME,
		'port'		=> \Application\DB_PORT,
		'socket'	=> \Application\DB_SOCKET
	);
	protected $errmode		= PDO::ERRMODE_EXCEPTION;

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
		// Initialize db handle
		try {
			$this->init_dbh();
		} catch( PDOException $e ) {
			throw $e;
		}
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
			$this->dbh = @new PDO($dsn, $this->db_info['user'], $this->db_info['password'], array(PDO::ATTR_PERSISTENT => true));
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
	
	/**
	 * is_unique function.
	 * 
	 * @access public
	 * @param mixed $table
	 * @param mixed $key
	 * @param mixed $val
	 * @return void
	 */
	public function is_unique($table, $key, $val) {
		try {
			$stmnt = $this->dbh->prepare("SELECT $key FROM $table WHERE $key = '$val'");
			$stmnt->execute();
			return !(bool)$stmnt->rowCount();
		} catch ( \Exception $e ) {
			$GLOBALS['JAAMS']['DEBUGGER']->debug_log(var_export($e, true));
			return false;
		}
	}
}