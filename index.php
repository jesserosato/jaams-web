<?php
// Some examples of how to use the JAAMS Framework (patent pending).
// Init
require_once('init.php');

// Load the Forms class (JAAMS_ROOT is defined in init.php above).
require_once(JAAMS_ROOT . '/Forms/init.php');

$template_dir_path = array('view'=>array(JAAMS_ROOT.'/templates'));

// Instantiate a JAAMSForms Form object, for a form named 'my_form'.
$form						= new JAAMSForms_Form('my_form', $template_dir_path);
$form->hierarchies['view']	= array('form');

// Create Project Information fieldset
$info_fieldset				= new JAAMSForms_Fieldset('info_fieldset', $template_dir_path);
$info_fieldset->label		= 'Project Information';
$info_fieldset->hierarchies['view'] = array('fieldset', 'info');

// Create Team Information fieldset
$team_fieldset 				= new JAAMSForms_Fieldset('team_fieldset');
$team_fieldset->label   	= 'Team Information';
//$team_fieldset->hierarchies['view'] = array('fieldset', 'team_fieldset');

// Create Accounts to Create fieldset
$account_fieldset 			= new JAAMSForms_Fieldset('account_fieldset');
$account_fieldset->label 	= 'Accounts To Create';

// Create Database Information fieldset
$database_fieldset 			= new JAAMSForms_Fieldset('database_fieldset', $template_dir_path);
$database_fieldset->label 	= 'Database Information';
$database_fieldset->hierarchies['view'] = array('fieldset', 'database');

// Create Project Account Information fieldset
$project_fieldset 			= new JAAMSForms_Fieldset("project_fieldset");
$project_fieldset->label 	= 'Project Account Information';


// Inputs for Project Information fieldset.
$participants				= new JAAMSForms_Input('participants');
$participants->label		= 'Number of Participants:';
$participants->type			= JAAMSForms_InputTypes::select;
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
								
$advisor					= new JAAMSForms_Input('advisor');
$advisor->label 			= 'Project Advisor:';
$advisor->type 				= JAAMSForms_InputTypes::text;
							
$advisor_email				= new JAAMSForms_Input('advisor_email');
$advisor_email->label 		= 'Project Advisor Email Address:';
$advisor_email->type 		= JAAMSForms_InputTypes::text;
							
$active						= new JAAMSForms_Input('active');
$active->label 				= 'How long will the Account be active?';
$active->type 				= JAAMSForms_InputTypes::select;
$active->args 				= array(
	'default_value'		=> '',
	'options'			=> array(
		'1'						=> 'One Semester',
		'2'						=> 'Two Semesters',
		'3'						=> 'Three Semester',
		'other'					=> 'Other'
	)
);
								
$active_other				= new JAAMSForms_Input('active_other');
$active_other->label 		= 'Other:';
$active_other->type 		= JAAMSForms_InputTypes::text;
$active_other->atts			= array('class' => "other");
							
$project_type 				= new JAAMSForms_Input('project_type');
$project_type->label 		= 'Project Type:';
$project_type->type 		= JAAMSForms_InputTypes::text;
								
$dept						= new JAAMSForms_Input('dept');
$dept->label 				= 'Class:';
$dept->type 				= JAAMSForms_InputTypes::select;
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
								
$class_no 					= new JAAMSForms_Input('class_no');
$class_no->type 			= JAAMSForms_InputTypes::text;
$class_no->label			= 'Class #: ';
$class_no->atts			= array('class' => "other");
								
$project_name 				= new JAAMSForms_Input('project_name');
$project_name->label 		= 'Project Name:';
$project_name->type 		= JAAMSForms_InputTypes::text;

// Groups for Project Information fieldset.
$semesters 					= new JAAMSForms_Group('semesters');
$semesters->inputs 			= array(
	'active'			=> $active,
	'active_other'		=> $active_other
);
$semesters->atts			= array('class' => "select-plus-other");
								
$class 						= new JAAMSForms_Group('class');
$class->inputs				= array(
	'dept'				=> $dept,
	'class_no'			=> $class_no
);
$class->atts				= array('class' => "select-plus-other");

// Inputs for Team Information fieldset
$first_name 				= new JAAMSForms_Input('first_name');
$first_name->label 			= 'First Name:';
$first_name->type 			= JAAMSForms_InputTypes::text;
							
$last_name 					= new JAAMSForms_Input('last_name');
$last_name->label 			= 'Last Name:';
$last_name->type 			= JAAMSForms_InputTypes::text;
							
$email 						= new JAAMSForms_Input('email');
$email->label 				= 'Email:';
$email->type 				= JAAMSForms_InputTypes::text;
							
$phone_number 				= new JAAMSForms_Input('phone_number');
$phone_number->label 		= 'Phone Number:';
$phone_number->type 		= JAAMSForms_InputTypes::text;

// Groups for Team Information fieldset
$member_info 				= new JAAMSForms_Group('member_info');
$member_info->label 		= 'Team Member';
$member_info->inputs 		= array(
	'first_name'		=> $first_name,
	'last_name' 		=> $last_name,
	'email'				=> $email,
	'phone_number'		=> $phone_number,
);

// Inputs for Accounts to Create fieldset
$account_type 				= new JAAMSForms_Input('account_type');
$account_type->label 		= 'Type of Account to Create:';
$account_type->type 		= JAAMSForms_InputTypes::select;
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
$mysql_host 				= new JAAMSForms_Input('mysql_host');
$mysql_host->label 			= 'MySQL Host Location:';
$mysql_host->type 			= JAAMSForms_InputTypes::radios;
$mysql_host->args 			= array(
	'default_value'		=> 'any',
	'options'			=> array(
		'localhost'				=> 'localhost *',
		'any'					=>	'% (any host) **',
	),
	'desc'				=> $mysql_host_desc
);

$permissions 				= new JAAMSForms_Input('permissions');
$permissions->label 		= 'Your Permissions:';
$permissions->type 			= JAAMSForms_InputTypes::radios;
$permissions->args 			= array(
	'default_value'		=> 'all',
	'options'			=> array(
		'all'					=> 'All',
		'std'					=> 'Standard (SELECT, INSERT, UPDATE, DELETE)',
		'other'					=> 'Other (please specify)'
	)
);

$other_permissions			= new JAAMSForms_Input('other_permissions', $template_dir_path);
$other_permissions->label 	= '';
$other_permissions->type 	= JAAMSForms_InputTypes::checkboxes;
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
		'references'
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
		'references'			=> 'REFERENCES',
	),
);
$other_permissions->hierarchies = array(
	'view'	=>	array('input', 'other_permissions')
);

$db_comments 				= new JAAMSForms_Input('db_comments');
$db_comments->label 		= 'Comments:';
$db_comments->type 			= JAAMSForms_InputTypes::textarea;

// Groups for Database Information fieldset
$db_permissions 			= new JAAMSForms_Group('db_permissions');
$db_permissions->inputs 	= array(
	'permissions'		=> $permissions,
	'other_permissions' => $other_permissions
);


// Inputs for Project Account Information fieldset
$disk_quota 				= new JAAMSForms_Input('disk_quota');
$disk_quota->label 			= 'Disk Quota (in MB):';
$disk_quota->type 			= JAAMSForms_InputTypes::text;

$unix_shell 				= new JAAMSForms_Input('unix_shell');
$unix_shell->label 			= 'Unix Shell:';
$unix_shell->type 			= JAAMSForms_InputTypes::select;
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

$project_comments 			= new JAAMSForms_Input('project_comments');
$project_comments->label 	= 'Comments:';
$project_comments->type 	= JAAMSForms_InputTypes::textarea;

$submit						= new JAAMSForms_Input('submit');
$submit->label				= 'Submit';
$submit->type				= JAAMSForms_InputTypes::submit;


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

$team_fieldset->groups 		= array('member_info' => $member_info);

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

$form->inputs			= array('submit' => $submit);

$form->print_html();
?>
<?php
/*
if ( empty ( $_POST['first_form'] ) ) {
	// Output the form
	$form->print_html();
} else {
	$form->sanitize();
	if ( $form->validate() ) {
		if ( $form->save() ) {
			//display success
		} else {
			$form->errors('database' => 'Unable to save data');
		}
	} else {
		$form->print_html();
	}
}
*/
