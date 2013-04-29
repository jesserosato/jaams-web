<?php
namespace CSC131\ECS\Models;
require_once(\JAAMS\ROOT.'/forms/init.php');
require_once(\Forms\ROOT.'/models/Base.model.php');


class Base extends \Forms\Models\Base {
	// METHODS
	// - PUBLIC
	public function __construct($controller) {
		parent::__construct($controller);
	}
	
	public function save() {
		$this->set_data($this->data);
		$GLOBALS['JAAMS']['DEBUGGER']->debug_log(print_r($this->data,true));
		$GLOBALS['JAAMS']['DEBUGGER']->debug_log("POST");
		$GLOBALS['JAAMS']['DEBUGGER']->debug_log($_POST);
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
		 	'DBAccountUName'	=> $this->_get_db_account_name(),	// varchar(30)
		 	'projName'			=> $this->data['project_name'],		//	varchar(20)
		 	'db_host'			=> $this->data['mysql_host'],		// enum('local', 'remote')
		);
		$permissions = $this->_get_permissions();
		/*
	 	foreach ( $permissions as $permission => $val ) {
		 	$dataman_dbaccounts[$permission] = $val;
	 	}
	 	*/
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
	
	protected function _get_semester_created() {
		$start = $this->get_semester_start(true, date('Y'), true);
		if (  time()
	}
	
	protected function _get_date_expires() {
		
	}
	
	protected function _get_permissions() {
		foreach ( $this->data['other_permissions'] as $perm ) {
			$ret['db_' . $perm] = 'y';
		}
		return $ret;
	}
	
	protected function _get_db_account_name() {
		
	}
	
	protected function _get_semester($semester, $year, $start)
}