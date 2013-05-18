<?php
namespace Application\Models;

// Load the SSH library.
set_include_path(\JAAMS\VENDOR_ROOT . '/phpseclib');
include('Net/SSH2.php');

class Base extends \Forms\Models\Base {
	// PROPERTIESit
	protected $ssh;

	// METHODS
	// - PUBLIC
	
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @param mixed $controller
	 * @param array $db_info (default: array())
	 * @param array $ssh_info (default: array())
	 * @return void
	 */
	public function __construct($controller, array $db_info = array(), array $ssh_info = array()) {
		parent::__construct($controller, $db_info);
	}
	
	/**
	 * __destruct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __destruct() {
		if ( ! empty( $this->ssh ) ) {
			$this->ssh->disconnect();
		}
	}
	
	/**
	 * save function.
	 * 
	 * @access public
	 * @return bool
	 */
	public function save() {
		$this->set_data($this->data);
		// Consolidate data
		$mysql_date			= date('Y-m-d');
		$semester_created	= $this->_get_semester_created();
		$date_expires		= $this->_get_date_expires();
		$class_or_major		= $this->_get_class_or_major();
		$server				= '';
		$db					= $this->is_database_request();
		$proj				= $this->is_project_request();
		// DBs: dataman + project :: TABLE: dataman_database
		$dataman_database = array(
			'projName'			=> $this->data['project_name'],
			'server'			=> $server,									// varchar(20) TODO: What is this?
		 	'semesterCreated'	=> $semester_created,		
		 	'dateExpires'		=> $date_expires,
		 	'dateCreated'		=> $mysql_date,
		 	'groupName'			=> $this->data['project_type'],
		 	'status'			=> 'Pending',
		 	'advisor_name'		=> $this->data['advisor'],
		 	'advisor_email'		=> $this->data['advisor_email'],
	 	);
	 	$dataman_people = array(
			// -- peopleID
			'deptMajor'			=> true,
			'pNameFirst'		=> true,
			'pNameLast'			=> true,
			'phoneNum'			=> true,
			'email'				=> true,
			'projName'			=> true,
		);
	 	if ( $db ) {
		 	// DB: dataman :: TABLE: dataman_dbaccounts
		 	$dataman_dbaccounts = array(
			 	'DBAccountUName'	=> $this->data['project_name'],		// varchar(30)
			 	'projName'			=> $this->data['project_name'],		//	varchar(20)
			 	'db_host'			=> $this->data['mysql_host'],		// enum('local', 'remote')
			);
			$permissions = $this->_get_permissions();
		 	foreach ( $permissions as $permission => $val ) {
			 	$dataman_dbaccounts[$permission] = $val;
		 	}
			// DB: dataman :: TABLE: dataman_history
			$dataman_history = empty($this->data['db_comments']) ? false : array(
				'hisDetail'		=> $this->data['db_comments'],
				'projName'		=> $this->data['project_name'],
				'hisShortDesc'	=> '',									// TODO: What is this?
				'hisDate'		=> $mysql_date,
			);
		}
		if ( $proj ) {
	 		// DB: project :: TABLE: project_info
			$project_info = array(
				'projName'			=> $this->data['project_name'],
				'quota'				=> $this->data['disk_quota'],
				'semesterCreated'	=> $semester_created,
				'groupName'			=> $this->data['project_type'],
				'dateExpires'		=> $date_expires,
				'dateCreated'		=> $mysql_date,
				'status'			=> 'Pending',
				'advisor_name'		=> $this->data['advisor'],
				'advisor_email'		=> $this->data['advisor_email'],
				'shell'				=> $this->data['unix_shell']
			);
			// DB: project :: TABLE: project_history
			$project_history = empty($this->data['project_comments']) ? false : array(
				'hisDetail'		=> $this->data['project_comments'],
				'projName'		=> $this->data['project_name'],
				'hisShortDesc'	=> '',
				'hisDate'		=> $mysql_date
			);
		}
		try {
			// Use a transaction to make sure all of our DB calls work before we actually commit changes.
			$this->dbh->beginTransaction();
			
		 	if ( $db ) {
		 		// DB Call - dataman::dataman_database
		 		$dataman_database = $this->_flatten_value($dataman_database);
				$query = $this->get_insert_query('dataman.dataman_database', $dataman_database);
				$stmnt = $this->dbh->prepare($query);
				ob_start();
				$stmnt->debugDumpParams();
				$contents = ob_get_contents();
				$GLOBALS['JAAMS']['DEBUGGER']->debug_log(var_export($contents, true));
				ob_end_clean();
				$stmnt->execute($dataman_database);
				
				// DB Call - dataman::dataman_dbaccounts
				$dataman_dbaccounts = $this->_flatten_value($dataman_dbaccounts);
				$query = $this->get_insert_query('dataman.dataman_dbaccounts', $dataman_dbaccounts);
				$stmnt = $this->dbh->prepare($query);
				$stmnt->execute($dataman_dbaccounts);

				// DB Call - dataman::dataman_history
				if ( $dataman_history ) {
					$query = $this->get_insert_query('dataman.dataman_history', $dataman_history);
					$stmnt = $this->dbh->prepare($query);
					$stmnt->execute($dataman_history);
				}
			}
			if ( $proj ) {
				// DB Call - project::project_info
				$query = $this->get_insert_query('project.project_info', $project_info);
				$stmnt = $this->dbh->prepare($query);
				$stmnt->execute($project_info);
				// DB Call - project::project_history
				if ( $project_history ) {
					$query = $this->get_insert_query('project.project_history', $project_history);
					$stmnt = $this->dbh->prepare($query);
					$stmnt->execute($project_history);
				}
			}
			// Loop through people and add them to DB
			$num_people = intval($this->data['participants']);
			for ( $i = 0; $i < $num_people; $i++ ) {
				// TODO: appropriately set deptMajor
				$dataman_people = array(
					// -- peopleID
					'deptMajor'			=> $this->data['major_' . $i],
					'pNamefirst'		=> $this->data['first_name_' . $i],
					'pNameLast'			=> $this->data['last_name_' . $i],
					'phoneNum'			=> $this->data['phone_number_' . $i],
					'email'				=> $this->data['email_' . $i],
					'projName'			=> $this->data['project_name'],
				);
				if ( $db ) {
					// Prep DB Calls to dataman::dataman_people and project::project_people.
					$db_query	= $this->get_insert_query('dataman.dataman_people', $dataman_people);
					$db_stmnt	= $this->dbh->prepare($db_query);
					$db_stmnt->execute($dataman_people);
				}
				if ( $proj ) {
					$proj_query = $this->get_insert_query('project.project_people', $dataman_people);
					$proj_stmnt = $this->dbh->prepare($proj_query);
					$proj_stmnt->execute($dataman_people);
				}
			}
			// Commit transaction.
			return $this->dbh->commit();
		} catch( \PDOException $e ) {
			// Something went wrong, roll back commits and pass along the exception.
			$this->dbh->rollBack();
			throw $e;
		}
	}
	
	
	/**
	 * set_ssh function.
	 * 
	 * @access public
	 * @param array $ssh_info
	 * @return bool
	 */
	public function set_ssh( array $ssh_info = array() ) {
		$user		= empty($ssh_info['user']) ? \Application\SSH_USER : $ssh_info['user'];
		$password	= empty($ssh_info['password']) ? \Application\SSH_PASSWORD : $ssh_info['password'];
		$ssh 		= @new \Net_SSH2(\Application\SSH_SERVER);
		$this->ssh = ( $ok = $ssh->login( $user, $password ) ) ? $ssh : null;
		return $ok;
	}
	
	/**
	 * get_insert_query function.
	 * 
	 * @access public
	 * @param string $table
	 * @param array $arr
	 * @return string
	 */
	public function get_insert_query( $table, array $arr ) {
		$keys = array_keys($arr);
	 	$cols = implode(', ', $keys);
	 	$placeholders = array();
	 	foreach ( $keys as $key ) {
		 	$placeholders[] = ":".$key;
	 	}
	 	$placeholders = implode(', ', $placeholders);
	 	return "INSERT INTO $table ($cols) value ($placeholders)";
	}
	
	/**
	 * is_valid_ecs_account function.
	 * 
	 * @access public
	 * @param string $user
	 * @return bool
	 */
	public function is_valid_ecs_account( $user ) {
		try {
			return (bool)$this->ssh->exec("ypcat passwd | grep $user");
		} catch ( Exception $e ) {
			return false;
		}
	}
	
	/**
	 * is_database_request function.
	 * 
	 * @access public
	 * @return bool
	 */
	public function is_database_request() {
		$this->set_data($this->data);
		return ($this->data['account_type'] == 'db') || ($this->data['account_type'] == 'both');
	}
	
	/**
	 * is_project_request function.
	 * 
	 * @access public
	 * @return bool
	 */
	public function is_project_request() {
		$this->set_data($this->data);
		return ($this->data['account_type'] == 'db') || ($this->data['account_type'] == 'both');
	}
	
	
	/**
	 * get_project_types function.
	 * 
	 * @access public
	 * @return array
	 */
	public function get_project_types() {
		$result = $this->query("SELECT groupName FROM dataman.dataman_group");
		$opts = array();
		foreach ( $result->fetchAll(\PDO::FETCH_COLUMN, 0) as $val ) {
			$opts[$val] = $val;
		}
		return $opts;
	}
	
	/**
	 * _flatten_value function.
	 * 
	 * @access protected
	 * @param array $arr
	 * @return array
	 */
	protected function _flatten_value( array $arr ) {
		$ret = array();
		foreach ( $arr as $key => $val ) {
			if ( is_array( $val ) ) {
				$ret[$key] = $val[0];
			} else {
				$ret[$key] = $val;
			}
		}
		return $ret;
	}
	
	/**
	 * _get_semester_created function.
	 * 
	 * @access protected
	 * @return string
	 */
	protected function _get_semester_created() {
		$now = time();
		if ( ( strtotime( date( 'Y-'.\Application\SPRING_START ) ) <= $now )
			&& ( $now <= strtotime( date( 'Y-'.\Application\SPRING_END ) ) ) )
			return 'Spring';
		else
			return 'Fall';
	}
	
	/**
	 * _get_date_expires function.
	 * 
	 * @access protected
	 * @return string
	 */
	protected function _get_date_expires() {
		if ( $this->data['active'] == 'other' ) {
			if ( is_int( $this->data['active_other'] ) ) {
				$semesters = intval($this->data['active_other']);
			} else {
				$semesters = \Application\DEFAULT_SEMESTERS_ACTIVE;
			}
		} else {
			$semesters = intval($this->data['active']);
		}
		// If it's only for one semester, just set it for the end of this semester.
		$semesters = $semesters == 1 ? 0 : $semesters;
		
		$sem_len = \Application\SEMESTER_LENGTH;
		$exp_time = $semesters > 1 ? strtotime("+" . $sem_len * $semesters . " months") : time();
		$exp_year = date('Y', $exp_time);
		
		
		if ( ( strtotime( date( $exp_year.'-'.\Application\SPRING_START ) ) <= $exp_time )
			&& ( $exp_time <= strtotime( date( $exp_year.'-'.\Application\SPRING_END ) ) ) )
			return date( $exp_year.'-'.\Application\SPRING_END );
		else
			return date( $exp_year.'-'.\Application\FALL_END );
	}
	
	/**
	 * _get_permissions function.
	 * 
	 * @access protected
	 * @return Array
	 */
	protected function _get_permissions() {
		$ret = array();
		if ( !empty ( $this->data['other_permissions'] ) ) {
			foreach ( $this->data['other_permissions'] as $perm ) {
				$ret['db_' . $perm] = 'y';
			}
		}
		return $ret;
	}
	
	protected function _get_class_or_major() {
		if ( intval($this->data['participants']) == 1 )
			return $this->data['major_0'];
		else
			return $this->data['dept'] . " " . $this->data['class_no'];
	}
}
