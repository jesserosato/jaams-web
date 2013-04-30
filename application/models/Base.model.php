<?php
namespace CSC131\ECS\Models;
<<<<<<< HEAD
require_once(\JAAMS\ROOT.'/forms/init.php');
require_once(\Forms\ROOT.'/models/Base.model.php');
=======

>>>>>>> 57359df9d6a55169e4262f7e80bae64210b2ba04
class Base extends \Forms\Models\Base {
	// METHODS
	// - PUBLIC
	public function __construct($controller) {
		parent::__construct($controller);
	}
	
<<<<<<< HEAD
	public function save() {
		// Define custom save logic and mysqli interactions.
=======
	
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
		$db					= ($this->data['account_type'] == 'db') || ($this->data['account_type'] == 'both');
		$proj				= ($this->data['account_type'] == 'db') || ($this->data['account_type'] == 'both');
		// DBs: dataman + project :: TABLE: dataman_database
		$dataman_database = array(
			'projName'			=> $this->data['project_name'],
			'server'			=> 'test',								// varchar(20) TODO: What is this?
		 	'semesterCreated'	=> $semester_created,
		 	'dateExpires'		=> $date_expires,
		 	'dateCreated'		=> $mysql_date,
		 	'status'			=> 'Pending',
		 	'advisor_name'		=> $this->data['advisor'],
		 	'advisor_email'		=> $this->data['advisor_email'],
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
		 	// DB: dataman :: TABLE: dataman_group
		 	$dataman_group = array(
			 	'groupName'		=> $this->data['project_type'], 		// 'student', 'faculty'.  TODO: Rest of the values?
			);
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
				// 'groupName'			=> $this->data['group_name'],
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
				$query = $this->get_insert_query('dataman.dataman_database', $dataman_database);
				$stmnt = $this->dbh->prepare($query);
				$stmnt->execute($dataman_database);
				// DB Call - dataman::dataman_dbaccounts
				$query = $this->get_insert_query('dataman.dataman_dbaccounts', $dataman_dbaccounts);
				$stmnt = $this->dbh->prepare($query);
				$stmnt->execute($dataman_dbaccounts);
				// DB_Call - dataman::dataman_group
				$query = $this->get_insert_query('project.project_info', $project_info);
				$stmnt = $this->dbh->prepare($query);
				$stmnt->execute($dataman_group);
				// DB Call - dataman::dataman_history
				$query = $this->get_insert_query('dataman.dataman_history', $dataman_history);
				$stmnt = $this->dbh->prepare($query);
				$stmnt->execute($dataman_history);
			}
			if ( $proj ) {
				// DB Call - project::dataman_database
				$query = $this->get_insert_query('project.dataman_database', $dataman_database);
				$stmnt = $this->dbh->prepare($query);
				$stmnt->execute($dataman_database);
				// DB Call - project::project_info
				$query = $this->get_insert_query('project.project_info', $project_info);
				$stmnt = $this->dbh->prepare($query);
				$stmnt->execute($project_info);
				// DB Call - project::project_history
				$query = $this->get_insert_query('project.project_history', $project_history);
				$stmnt = $this->dbh->prepare($query);
				$stmnt->execute($project_history);
			}
			// Prep DB Calls to dataman::dataman_people and project::project_people.
			$db_query	= $this->get_insert_query('dataman.dataman_people', $dataman_people);
			$db_stmnt	= $this->dbh->prepare($query);
			$proj_query = $this->get_insert_query('project.project_people', $dataman_people);
			$proj_stmnt = $this->dbh->prepare($query);
			$num_people = intval($this->data['participants']);
			// Loop through people and add them to DB
			for ( $i = 0; $i < $num_people; $i++ ) {
				$dataman_people = array(
					// -- peopleID
					'deptMajor'			=> $this->data['dept'],
					'pNameLast'			=> $this->data['first_name_'. $i],
					'pNameFirst'		=> $this->data['last_name_' . $i],
					'phoneNum'			=> $this->data['phone_number_' . $i],
					'email'				=> $this->data['email_' . $i],
					'projName'			=> $this->data['project_name'],
				);
				if ( $db ) {
					$db_stmnt->execute($dataman_history);
				}
				if ( $proj ) {
					$proj_stmnt->execute($dataman_history);
				}
			}
			// Commit transaction.
			return $this->dbh->commit();
		} catch( \PDOException $e ) {
			// Something went wrong, roll back commits.
			$this->dbh->rollBack();
			return false;
		}
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
	 	foreach ( $keys as $key ) {
		 	$placeholders[] = ":".$key;
	 	}
	 	$placeholders = implode(', ', $placeholders);
	 	return "INSERT INTO $table ($cols) value ($placeholders)";
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
			return date('FALL_END');
	}
	
	/**
	 * _get_permissions function.
	 * 
	 * @access protected
	 * @return Array
	 */
	protected function _get_permissions() {
		foreach ( $this->data['other_permissions'] as $perm ) {
			$ret['db_' . $perm] = 'y';
		}
		return $ret;
>>>>>>> 57359df9d6a55169e4262f7e80bae64210b2ba04
	}
}