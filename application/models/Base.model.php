<?php
namespace CSC131\ECS\Models;

// Load the SSH library.
set_include_path(\JAAMS\VENDOR_ROOT . '/phpseclib');
include('Net/SSH2.php');

class Base extends \Forms\Models\Base {
	// PROPERTIES
	protected $ssh;

	// METHODS
	// - PUBLIC
	public function __construct($controller, array $db_info = array(), array $ssh_info = array()) {
		parent::__construct($controller, $db_info);
		$this->ssh = $this->get_ssh($ssh_info);
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
		$db					= $this->is_db_request();
		$proj				= $this->is_proj_request();
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
			$dataman_history = array(
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
			$project_history = array(
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
				$stmnt->execute($dataman_database);
				// DB Call - dataman::dataman_dbaccounts
				$dataman_dbaccounts = $this->_flatten_value($dataman_dbaccounts);
				$query = $this->get_insert_query('dataman.dataman_dbaccounts', $dataman_dbaccounts);
				$stmnt = $this->dbh->prepare($query);
				$stmnt->execute($dataman_dbaccounts);

				// DB Call - dataman::dataman_history
				$query = $this->get_insert_query('dataman.dataman_history', $dataman_history);
				$stmnt = $this->dbh->prepare($query);
				$stmnt->execute($dataman_history);
			}
			if ( $proj ) {
				// DB Call - project::project_info
				$query = $this->get_insert_query('project.project_info', $project_info);
				$stmnt = $this->dbh->prepare($query);
				$stmnt->execute($project_info);
				// DB Call - project::project_history
				$query = $this->get_insert_query('project.project_history', $project_history);
				$stmnt = $this->dbh->prepare($query);
				$stmnt->execute($project_history);
			}
			// Loop through people and add them to DB
			$num_people = intval($this->data['participants']);
			for ( $i = 0; $i < $num_people; $i++ ) {
				// TODO: appropriately set deptMajor
				$dataman_people = array(
					// -- peopleID
					'deptMajor'			=> $class_or_major,
					'pNamefirst'		=> $this->data['first_name_'. $i],
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
			// Something went wrong, roll back commits.
			$this->dbh->rollBack();
			throw $e;
		}
	}
	
	public function get_ssh( array $ssh_info ) {
		$user		= empty($ssh_info['user']) ? \CSC131\ECS\SSH_USER : $ssh_info['user'];
		$password	= empty($ssh_info['password']) ? \CSC131\ECS\SSH_PASSWORD : $ssh_info['password'];
		$ssh 		= new \Net_SSH2('athena.ecs.csus.edu');
		if ( !$ssh->login( $user, $password ) )
			throw new Exception("Unable to connect to server in " . __FILE__ . " on line " . __LINE__ . ".");
		return $ssh;
	}
	
	/**
	 * get_insert_query function.
	 * 
	 * @access public
	 * @param string $table
	 * @param array $arr
	 * @return void
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
	
	public function is_valid_ecs_account( $user ) {
		try {
			return (bool)$this->ssh->exec("ypcat passwd | grep $user");
		} catch ( Exception $e ) {
			return false;
		}
	}
	
	public function is_db_request() {
		$this->set_data($this->data);
		return ($this->data['account_type'] == 'db') || ($this->data['account_type'] == 'both');
	}
	
	public function is_proj_request() {
		$this->set_data($this->data);
		return ($this->data['account_type'] == 'db') || ($this->data['account_type'] == 'both');
	}
	
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
		if ( ( strtotime( date( \CSC131\ECS\SPRING_START ) ) <= $now )
			&& ( $now <= strtotime( date( \CSC131\ECS\SPRING_END ) ) ) )
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
				$semesters = \CSC131\ECS\DEFAULT_SEMESTERS_ACTIVE;
			}
		} else {
			$semesters = intval($this->data['active']);
		}
		$sem_len = \CSC131\ECS\SEMESTER_LENGTH;
		$exp_time = strtotime("+" . $sem_len * $semesters . " months");
		
		
		if ( ( strtotime( date( \CSC131\ECS\SPRING_START ) ) <= $exp_time ) 
			&& ( $exp_time <= strtotime( date( \CSC131\ECS\SPRING_END ) ) ) )
			return date( \CSC131\ECS\SPRING_END );
		else
			return date( \CSC131\ECS\FALL_END );
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
		return intval($this->data['participants']) > 1 ? $this->data['dept'] . $this->data['class_no'] : $this->data['major'];
	}
}
