<?php


// Some examples of how to use the JAAMS Framework (patent pending).
// Init (Loads all modules).
require_once('init.php');
require_once('application/localization/error_msgs.php');
use \Forms\Controllers\Fieldset as Fieldset;
use \Forms\Controllers\Group as Group;
use \Forms\Controllers\Input as Input;
use \Forms\Controllers\InputTypes as InputTypes;
use \Forms\Controllers\InputValidators as InputValidators;
use \Application\Controllers\Form as Form;
use \Application\Controllers\AdminEmail as AdminEmail;
use \Application\Controllers\UserEmail as UserEmail;
use \Application\Models\Base as FormModel;

$template_dir_path			= array('view' => array(\JAAMS\ROOT.'/application/templates'));
$model_dir_path				= array('model' => array(\Forms\ROOT.'/models'));

// Instantiate a JAAMSForms Form object, for a form named 'my_form'.
$form						= new Form('my_form', $template_dir_path);
try {
	// Instantiate the model
	$form->model				= new FormModel($form);
} catch ( \PDOException $e ) {
	echo '<h2 class="error">' . $error_msgs['database_connection'] . '</h2>';
	$GLOBALS['JAAMS']['DEBUGGER']->debug_log(var_export($e, true));
	die();
} catch (  \Exception $e ) {
	echo '<h2 class="error">' . $error_msgs['ssh_connection'] . '</h2>';
	$GLOBALS['JAAMS']['DEBUGGER']->debug_log(var_export($e, true));
	die();
}
$form->hierarchies['view']		= array('form');
$form->atts['action']			= $_SERVER['PHP_SELF'];
$form->args['validator']		= array('has_ecs_email', 'unique_project_name');
$form->args['error_msgs']		= $error_msgs;


// Create Project Information fieldset
$info_fieldset				= new Fieldset('info_fieldset', $template_dir_path);
$info_fieldset->label		= 'Project Information';
$info_fieldset->hierarchies['view'] = array('fieldset', 'info');

// Create Team Information fieldset
$team_fieldset 				= new Fieldset('team_fieldset');
$team_fieldset->label   	= 'Team Information';
//$team_fieldset->hierarchies['view'] = array('fieldset', 'team_fieldset');

// Create Accounts to Create fieldset
$account_fieldset 			= new Fieldset('account_fieldset');
$account_fieldset->label 	= 'Accounts To Create';

// Create Database Information fieldset
$database_fieldset 			= new Fieldset('database_fieldset', $template_dir_path);
$database_fieldset->label 	= 'Database Information';
$database_fieldset->hierarchies['view'] = array('fieldset', 'database');

// Create Project Account Information fieldset
$project_fieldset 			= new Fieldset("project_fieldset");
$project_fieldset->label 	= 'Project Account Information';


// Inputs for Project Information fieldset.
$participants				= new Input('participants');
$participants->label		= 'Number of Participants:';
$participants->type			= InputTypes::select;
$participants->args 			= array(
	'default_value'		=> '1',
	'options'			=> array(
		'1'						=> '1',
		'2'						=> '2',
		'3'						=> '3',
		'4'						=> '4',
		'5'						=> '5',
		'6'						=> '6',
		'7'						=> '7',
		'8'						=> '8',
		'9'						=> '9',
		'10'					=> '10'
	),
);

$advisor					= new Input('advisor');
$advisor->label 			= 'Project Advisor:';
$advisor->type 				= InputTypes::text;
$advisor->args              = array(
	'validator' 		=> 	array('required','only_letters',),
	'error_msgs'		=>	$error_msgs['advisor']
);


$advisor_email				= new Input('advisor_email');
$advisor_email->label 		= 'Project Advisor Email Address:';
$advisor_email->type 		= InputTypes::text;
$advisor_email->args  		= array(
	'validator'	 		=> array('required','email',),
	'error_msgs' 		=> $error_msgs['advisor_email']

); 

							
$active						= new Input('active');
$active->label 				= 'How long will the Account be active?';
$active->type 				= InputTypes::select;
$active->args 				= array(
	'default_value'		=> '',
	'options'			=> array(
		'1'						=> 'One Semester',
		'2'						=> 'Two Semesters',
		'3'						=> 'Three Semester',
		'other'					=> 'Other'
	)
);
$active_other				= new Input('active_other');
$active_other->label 		= 'Other:';
$active_other->type 		= InputTypes::text;
$active_other->atts			= array(
	'class' => "other"		
	
);
							
$project_type 				= new Input('project_type');
$project_type->label 		= 'Project Type:';
$project_type->type 		= InputTypes::select;
$project_type->args			= array(
	'default_value'		=> '',
	'options'			=> array(
		'student'				=> 'Student',

	),
);
								
$dept						= new Input('dept');
$dept->label 				= 'Class:';
$dept->type 				= InputTypes::select;
$dept->args 				= array(
	'default_value'		=> 'ce',
	'options'			=> array(
		'ce'					=> 'CE',
		'cpe'					=> 'CpE',
		'csc'					=> 'CSC',
		'cm'					=> 'CM', 	
		'eee'					=> 'EEE',
		'me'					=> 'ME',
		'other' 				=> 'Other'
	),
);

$class_no 					= new Input('class_no');
$class_no->type 			= InputTypes::text;
$class_no->label			= 'Class #: ';
$class_no->atts				= array('class' => "other");
$class_no->args             = array(
	'validator'			=>	array('required','only_numbers',),
	'error_msgs'		=> 	$error_msgs['class_no']	
);

$major						= new Input('major');
$major->label				= 'Major:';
$major->type				= InputTypes::text;

$project_name 				= new Input('project_name');
$project_name->label 		= 'Project Name:';
$project_name->type 		= InputTypes::text;
$project_name->args         = array(
	'validator' 		=> array('required','only_letters',),
	'error_msgs'		=>	$error_msgs['project_name']
);
								

// Groups for Project Information fieldset.
$semesters 					= new Group('semesters');
$semesters->inputs 			= array(
	'active'			=> $active,
	'active_other'		=> $active_other
);
$semesters->atts			= array('class' => "select-plus-other");

$class 						= new Group('class');
$class->inputs				= array(
	'dept'				=> $dept,
	'class_no'			=> $class_no,
	'major'				=> $major
);
$class->atts				= array('class' => "select-plus-other");

// Groups for Team Information fieldsets
for ( $i = 0; $i < \Application\MAX_PARTICIPANTS; $i++ ) {
	// Inputs for Team Members fieldsets
	$first_name 				= new Input('first_name_'.$i);
	$first_name->label 			= 'First Name:';
	$first_name->type 			= InputTypes::text;
	$first_name->args           = array(
		'validator' 		=> array('required','only_letters',),
		'error_msgs'		=> $error_msgs['first_name']
	);

	$last_name 					= new Input('last_name_' . $i);
	$last_name->label 			= 'Last Name:';
	$last_name->type 			= InputTypes::text;
	$last_name->args           = array(
		'validator' 		=> array('required','only_letters',),
		'error_msgs'		=> $error_msgs['last_name']
	);

	$email 						= new Input('email_' . $i);
	$email->label 				= 'Email:';
	$email->type 				= InputTypes::text;
	$email->args           = array(
		'validator'			=> array('required','email',),
		'error_msgs'		=> $error_msgs['email']
	);

	$phone_number 				= new Input('phone_number_' . $i);
	$phone_number->label 		= 'Phone Number:';
	$phone_number->type 		= InputTypes::text;
	$phone_number->args 		= array(
		'validator'			=> array('required','phone',),
		'error_msgs'		=> $error_msgs['phone_number']
	);
	
	$member_info[$i] 				= new Fieldset('member_info_'.$i);
	$member_info[$i]->atts			= array('id' => 'member_info_' . $i);
	$member_info[$i]->label 		= 'Team Member ' . ($i  + 1);
	$member_info[$i]->inputs 		= array(
		'first_name_'.$i		=> $first_name,
		'last_name_'.$i 		=> $last_name,
		'email_'.$i				=> $email,
		'phone_number_'.$i		=> $phone_number,
	);
}

// Inputs for Accounts to Create fieldset
$account_type 				= new Input('account_type');
$account_type->label 		= 'Type of Account to Create:';
$account_type->type 		= InputTypes::select;
$account_type->args 		= array(
	'default_value'		=> 'both',
	'options'			=> array(
		'both'					=> 'MySQL Database and Project Account',
		'db'					=> 'MySQL Database',
		'pa'					=> 'Project Account'
	)
);

// Inputs for Database Information fieldset
$mysql_host_desc			= '<p class="info">* Localhost: Must be logged into assigned MySQL server - i.e. athena<br />** % (Any Host): Must use -h <server name> - i.e. -h athena; commonly used for web applications</p>';
$mysql_host 				= new Input('mysql_host');
$mysql_host->label 			= 'MySQL Host Location:';
$mysql_host->type 			= InputTypes::radios;
$mysql_host->args 			= array(
	'default_value'		=> array('any'),
	'options'			=> array(
		'localhost'				=> 'localhost *',
		'any'					=>	'% (any host) **',
	),
	'desc'				=> $mysql_host_desc
);

$permissions 				= new Input('permissions');
$permissions->label 		= 'Your Permissions:';
$permissions->type 			= InputTypes::radios;
$permissions->args 			= array(
	'default_value'		=> array('all'),
	'options'			=> array(
		'all'					=> 'All',
		'std'					=> 'Standard (SELECT, INSERT, UPDATE, DELETE)',
		'other'					=> 'Other (please specify)'
	)
);

$other_permissions			= new Input('other_permissions', $template_dir_path);
$other_permissions->label 	= '';
$other_permissions->type 	= InputTypes::checkboxes;
$other_permissions->args 	= array(
	// Values are weird when dealing with multiple checkboxes.
	'default_value'		=> array(
		'alter',
		'insert',
		'create',
		'delete',
		'select',
		'drop',
		'index',
		'update',
		'reference',
	),
	'options'			=> array(
		'alter'					=> 'ALTER',
		'insert'				=> 'INSERT',
		'create'				=> 'CREATE',
		'delete'				=> 'DELETE',
		'select'				=> 'SELECT',
		'drop'					=> 'DROP',
		'index'					=> 'INDEX',
		'update'				=> 'UPDATE',
		'reference'				=> 'REFERENCES',
	),
);
$other_permissions->hierarchies = array(
	'view'	=>	array('input', 'other_permissions')
);

$db_comments 				= new Input('db_comments');
$db_comments->label 		= 'Comments:';
$db_comments->type 			= InputTypes::textarea;

// Groups for Database Information fieldset
$db_permissions 			= new Group('db_permissions');
$db_permissions->inputs 	= array(
	'permissions'		=> $permissions,
	'other_permissions' => $other_permissions
);


// Inputs for Project Account Information fieldset
$disk_quota 				= new Input('disk_quota');
$disk_quota->label 			= 'Disk Quota (in MB):';
$disk_quota->type 			= InputTypes::text;
$disk_quota->args 			= array(
	'validator' 			=> array('greater_zero', 'required'),
	'error_msgs' 			=> $error_msgs['disk_quota'],
	'default_value'			=> '1500'
);

$unix_shell 				= new Input('unix_shell');
$unix_shell->label 			= 'Unix Shell:';
$unix_shell->type 			= InputTypes::select;
$unix_shell->args 			= array(
	'default_value'		=> 'csh',
	'options'			=> array(
		'csh'					=> 'Csh',
		'bash'					=> 'Bash',
		'ksh'					=> 'Ksh',
		'sh'					=> 'Sh',
		'tcsh'					=> 'Tcsh'
	)
);

$project_comments 			= new Input('project_comments');
$project_comments->label 	= 'Comments:';
$project_comments->type 	= InputTypes::textarea;

$submit						= new Input('ecs_submit');
$submit->label				= 'Submit';
$submit->type				= InputTypes::submit;


// Be careful when setting fieldsets, groups and inputs, that you don't overwrite
// previously added elements.  See php array_merge.
$info_fieldset->inputs		= array(
	'participants' 		=> $participants,
	'advisor' 			=> $advisor,
	'advisor_email'		=> $advisor_email,
	'project_type'		=> $project_type,
	'project_name'		=> $project_name
);

$info_fieldset->groups		= array(
	'semesters'			=> $semesters,
	'class'				=> $class
);

foreach ( $member_info as $key => $fieldset ) {
	$team_fieldset->fieldsets['member_info_' . $key] = $fieldset;
}

$account_fieldset->inputs 	= array('account_type' => $account_type);

$database_fieldset->inputs 	= array('mysql_host' => $mysql_host,
									'db_comments' => $db_comments);
$database_fieldset->groups 	= array('db_permissions' => $db_permissions);

$project_fieldset->inputs 	= array(
	'disk_quota'		=> $disk_quota,
	'unix_shell'		=> $unix_shell,
	'project_comments'	=> $project_comments
);

// Register the fieldset with the form.
// Be careful when setting fieldsets, groups and inputs, that you don't overwrite
// previously added elements.  See php array_merge.
$form->fieldsets			= array(
	'info_fieldset'		=> $info_fieldset,
	'team_fieldset'		=> $team_fieldset,
	'account_fieldset'	=> $account_fieldset,
	'database_fieldset'	=> $database_fieldset,
	'project_fieldset'	=> $project_fieldset
);

$form->inputs			= array('ecs_submit' => $submit);

if ( empty ( $_POST['ecs_submit'] ) ) {
	// Output the form
	$form->print_html();
} else {
	$form->sanitize();
	if ( $form->validate() ) {
		echo '<h2>Thank you for your submission!</h2>';
		try {
			$form->save();
			$data = $form->model->get_data();
			$admin_email	= new AdminEmail($data);
			$user_email		= new UserEmail($data);
			if ( ! $admin_email->send() ) {
				$GLOBALS['JAAMS']['DEBUGGER']->debug_log("Error sending email.");
			}
			if ( $form->model->is_database_request() ) {
				$user_email->hierarchies['view'] = array('email','user', 'database');
				if ( ! $user_email->send() ) {
					$form->errors['user_project_email'] = empty($error_msgs['user_project_email']) ? 'Error sending project account request confirmation email.' : $error_msgs['user_project_email'];
				}
			}
			if ( $form->model->is_project_request() ) {
				$user_email->hierarchies['view'] = array('email','user', 'project');
				if ( ! $user_email->send() ) {
					$form->errors['user_database_email'] = empty($error_msgs['user_database_email']) ? 'Error sending project account request confirmation email.' : $error_msgs['user_database_email'];
				}
			}
			
			include(\JAAMS\APP_ROOT.'/templates/success.php');
		} catch( \Exception $e ) {
			$form->errors['database_save'] = empty($error_msgs['database_save']) ? 'Error saving data to database' : $error_msgs['database_save'];
			$form->print_html();
			$GLOBALS['JAAMS']['DEBUGGER']->debug_log(var_export($e, true));
		}
	} else {
		$form->print_html();
	}
}
