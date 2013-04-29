<?php
namespace CSC131\ECS\Models;
require_once(\JAAMS\ROOT.'/forms/init.php');
require_once(\Forms\ROOT.'/models/Base.model.php');

use \CSC131\ECS\SPRING_START as SPRING_START;
use \CSC131\ECS\SPRING_END as SPRING_END;
use \CSC131\ECS\FALL_START as FALL_START;
use \CSC131\ECS\FALL_END as FALL_END;


class Base extends \Forms\Models\Base {
	// METHODS
	// - PUBLIC
	public function __construct($controller) {
		parent::__construct($controller);
	}
	
	public function save() {
		$this->set_data($this->data);
		// Consolidate data
		$mysql_date			= date('Y-m-d');
		$semester_created	= $this->_get_semester_created();
		$date_expires		= $this->_get_date_expires();
		// calculate deleted date from active/active_other
		// Save once for dataman and once for project
		$dataman_database = array(
			// DBs: dataman + project :: TABLE: dataman_database
			'projName'			=> $this->data['project_name'],
			// -- server 											// varchar(20) ?
		 	'semesterCreated'	=> $semester_created,
		 	'dateExpires'		=> $date_expires,
		 	'dateCreated'		=> $mysql_date,
		 	// -- dateDeleted	date
		 	'status'			=> 'Pending',
		 	'advisor_name'		=> $this->data['advisor'],
		 	'advisor_email'		=> $this->data['advisor_email'],
	 	);
	 	// DB Request - dataman AND project
	 	
	 	// DB: dataman :: TABLE: dataman_dbaccounts
	 	$dataman_dbaccounts = array(
		 	'DBAccountUName'	=> $this->data['project_name'],	// varchar(30)
		 	'projName'			=> $this->data['project_name'],		//	varchar(20)
		 	'db_host'			=> $this->data['mysql_host'],		// enum('local', 'remote')
		);
		$permissions = $this->_get_permissions();
	 	foreach ( $permissions as $permission => $val ) {
		 	$dataman_dbaccounts[$permission] = $val;
	 	}
	 	// DB Request - dataman
	 	
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
	 	// DB Request - project
	 	
	 	// DB: dataman :: TABLE: dataman_group
	 	$dataman_group = array(
		 	'groupName'		=> $this->data['project_type'], 			// 'student', 'faculty'.  TODO: Rest of the values?
		);
		// DB_Request - dataman
		
		// DB: dataman :: TABLE: dataman_history
		$dataman_history = array(
			'hisDetail'		=> $this->data['db_comments'],
			'projName'		=> $this->data['project_name'],
			'hisShortDesc'	=> '',
			'hisDate'		=> $mysql_date,						// TODO: What is this?
		);
		// DB Request - dataman
	 	
	 	// DB: project :: TABLE: project_history
	 	$project_history = array(
	 		'hisDetail'		=> $this->data['project_comments'],
	 		'projName'		=> $this->data['project_name'],
	 		'hisShortDesc'	=> '',
	 		'hisDate'		=> $mysql_date
	 	);
	 	
	 	$num_people = intval($this->data['participants']);
	 	// TABLE: dataman::dataman_people
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
			 // DB Request - dataman AND project
		}
		
		return true;
	}
	
	
	/**
	 * _get_semester_created function.
	 * 
	 * @access protected
	 * @return string
	 */
	protected function _get_semester_created() {
		$now = time();
		if ( ( strtotime( date( SPRING_START ) ) <= $now ) && ( $now <= strtotime( date( SPRING_END ) ) ) )
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
		$exp_time = strtotime("+" . 6 * $this->data['semesters'] . " months");
		if ( ( strtotime( date( SPRING_START ) ) <= $exp_time ) && ( $exp_time <= strtotime( date( SPRING_END ) ) ) )
			return date( SPRING_END );
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
	}
}