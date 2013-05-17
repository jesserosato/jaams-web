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
		$this->_init_dbh();
		if ( empty( $this->dbh ) )
			throw new \Ecxeption("Unable to connect to database.");
	}
	
	/**
	 * get_dsn function.
	 * TODO: Add more drivers.
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
	 * query function.
	 * Wrapper for \PDO::query()
	 * 
	 * @access public
	 * @param string $query (default: '')
	 * @return PDOStatement
	 */
	public function query( $query = '' ) {
		return $this->dbh->query($query);
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
		$stmnt = $this->dbh->prepare("SELECT $key FROM $table WHERE $key = '$val'");
		$stmnt->execute();
		return !(bool)$stmnt->rowCount();
	}
	
	// - PROTECTED
	
	/**
	 * _init_dbh function.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _init_dbh( ) {
		$dsn = $this->get_dsn();
		// Connect to the db.
		$this->dbh = @new PDO($dsn, $this->db_info['user'], $this->db_info['password']);
		$this->dbh->setAttribute(PDO::ATTR_ERRMODE, $this->errmode);
	}
}