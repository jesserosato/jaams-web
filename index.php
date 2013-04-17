<?php
// Some examples of how to use the JAAMS Framework (patent pending).
// Init
require_once('init.php');

// Load the Forms class (JAAMS_ROOT is defined in init.php above).
require_once(JAAMS_ROOT . '/Forms/init.php');

// Instantiate a JAAMSForms Form object, for a form named 'my_form'.
$form				= new JAAMSForms_Form('my_form');

// Create Project Information fieldset
$info_fieldset			= new JAAMSForms_Fieldset('info_fieldset');
$info_fieldset->label	= 'Project Information';

// Create Team Information fieldset
$team_fieldset 			= new JAAMSForms_Fieldset('team_fieldset');
$team_fieldset->label   = 'Team Information';

// Create Accounts to Create fieldset
$account_fieldset 			= new JAAMSForms_Fieldset('account_fieldset');
$account_fieldset->label 	= 'Accounts To Create';

// Create Database Information fieldset
$database_fieldset 			= new JAAMSForms_Fieldset('database_fieldset');
$database_fieldset->label 	= 'Database Information';

// Create Project Account Information fieldset
$project_fieldset 			= new JAAMSForms_Fieldset("project_fieldset");
$project_fieldset->label 	= 'Project Account Information';


// Inputs for Project Information fieldset.
$particpants						= new JAAMSForms_Input('particpants');
$particpants->label					= 'Number of Particpants';
$particpants->type					= JAAMSForms_InputTypes::select;
$particpants->args 		= array('Select Number of Particpants', 1, 2, 3, 4, 5, 6);

$advisor				= new JAAMSForms_Input('advisor');
$advisor->label 		= 'Project Advisor';
$advisor->type 			= JAAMSForms_InputTypes::text;

$advisor_email			= new JAAMSForms_Input('advisor_email');
$advisor_email->label 	= 'Project Advisor Email Address';
$advisor_email->type 	= JAAMSForms_InputTypes::text;

$active					= new JAAMSForms_Input('active');
$active->label 			= 'How long will the Account be active?';
$active->type 			= JAAMSForms_InputTypes::select;
$active->args 			= array('Select Number of Semesters', 'One Semester', 'Two Semesters', 'Three Semester', 'Other');

$active_other			= new JAAMSForms_Input('active_other');
$active_other->label 	= 'Other';
$active_other->type 	= JAAMSForms_InputTypes::text;

$project_type 			= new JAAMSForms_Input('project_type');
$project_type->label 	= 'Project Type';
$project_type->type 	= JAAMSForms_InputTypes::text;

$dept					= new JAAMSForms_Input('dept');
$dept->type 			= JAAMSForms_InputTypes::select;
$dept->args 			= array('CE', 'CpE', 'CSC', 'CM', 'EEE', 'ME', 'Other');

$class_no 				= new JAAMSForms_Input('class_no');
$class_no->type 		= JAAMSForms_InputTypes::text;

$project_name 			= new JAAMSForms_Input('project_name');
$project_name->label 	= 'Project Name';
$project_name->type 	= JAAMSForms_InputTypes::text;

// Groups for Project Information fieldset.
$semesters 				= new JAAMSForms_Group('semesters');
$semesters->inputs 		= array('active' => $active,
								'active_other' => $active_other);

$class 					= new JAAMSForms_Group('class');
$class->inputs			= array('dept' => $dept,
								'class_no' => $class_no);

// Inputs for Team Information fieldset
$first_name 			= new JAAMSForms_Input('first_name');
$first_name->label 		= 'First Name';
$first_name->type 		= JAAMSForms_InputTypes::text;

$last_name 				= new JAAMSForms_Input('last_name');
$last_name->label 		= 'Last Name';
$last_name->type 		= JAAMSForms_InputTypes::text;

$email 					= new JAAMSForms_Input('email');
$email->label 			= 'Email';
$email->type 			= JAAMSForms_InputTypes::text;

$phone_number 			= new JAAMSForms_Input('phone_number');
$phone_number->label 	= 'Phone Number';
$phone_number->type 	= JAAMSForms_InputTypes::text;

// Groups for Team Information fieldset
$member_info 			= new JAAMSForms_Group('member_info');
$member_info->label 	= 'Team Member';
$member_info->inputs 	= array('first_name' => $first_name,
								'last_name' => $last_name,
								'email' => $email,
								'phone_number' => $phone_number,
								'project_name' => $project_name);

// Inputs for Accounts to Create fieldset
$account_type 				= new JAAMSForms_Input('account_type');
$account_type->label 		= 'Type of Account to Create';
$account_type->type 		= JAAMSForms_InputTypes::select;
$account_type->args 		= array('MySQL Database and Project Account', 'MySQL Database', 'Project Account');

// Inputs for Database Information fieldset
$mysql_host 				= new JAAMSForms_Input('mysql_host');
$mysql_host->label 			= 'MySQL Host Location';
$mysql_host->type 			= JAAMSForms_InputTypes::radios;
$mysql_host->args 			= array('localhost *', '% (any host) **');

$permissions 				= new JAAMSForms_Input('permissions');
$permissions->label 		= 'Your Permissions';
$permissions->type 			= JAAMSForms_InputTypes::radios;
$permissions->args 			= array('All', 'Standard (SELECT, INSERT, UPDATE, DELETE', 'Other (please specify)');

$other_permissions			= new JAAMSForms_Input('other_permissions');
$other_permissions->label 	= 'Other';
$other_permissions->type 	= JAAMSForms_InputTypes::checkboxes;
$other_permissions->args 	= array('ALTER', 'INSERT', 'CREATE', 'DELETE', 'SELECT', 'DROP', 'INDEX',
																	'UPDATE', 'REFERENCES');

$db_comments 				= new JAAMSForms_Input('db_comments');
$db_comments->label 		= 'Comments';
$db_comments->type 			= JAAMSForms_InputTypes::textarea;

// Groups for Database Information fieldset
$db_permissions 			= new JAAMSForms_Group('db_permissions');
$db_permissions->inputs 	= array('permissions' => $permissions,
									'other_permissions' => $other_permissions);


// Inputs for Project Account Information fieldset
$disk_quota 				= new JAAMSForms_Input('disk_quota');
$disk_quota->label 			= 'Disk Quota (in MB)';
$disk_quota->type 			= JAAMSForms_InputTypes::text;

$unix_shell 				= new JAAMSForms_Input('unix_shell');
$unix_shell->label 			= 'Unix Shell';
$unix_shell->type 			= JAAMSForms_InputTypes::select;
$unix_shell->args 			= array('Csh', 'Bash', 'Ksh', 'Sh', 'Tcsh');

$project_comments 			= new JAAMSForms_Input('project_comments');
$project_comments->label 	= 'Comments';
$project_comments->type 	= JAAMSForms_InputTypes::textarea;

>>>>>>> bfc033b796e31ff5415d21d639b6f0c86912d183

// Be careful when setting fieldsets, groups and inputs, that you don't overwrite
// previously added elements.  See php array_merge.
$info_fieldset->inputs	= array('particpants' => $particpants, 
									'advisor' => $advisor,
									'advisor_email' => $advisor_email,
									'project_type' => $project_type,
									'project_name' => $project_name);

$info_fieldset->groups 	= array('semesters' => $semesters,
									'class' => $class);

$team_fieldset->groups 		= array('member_info' => $member_info);

$account_fieldset->inputs 	= array('account_type' => $account_type);

$database_fieldset->inputs 	= array('mysql_host' => $mysql_host);
$database_fieldset->groups 	= array('db_permissions' => $db_permissions);

$project_fieldset->inputs 	= array('disk_quota' => $disk_quota,
									'unix_shell' => $unix_shell,
									'project_comments' => $project_comments);

// Register the fieldset with the form.
// Be careful when setting fieldsets, groups and inputs, that you don't overwrite
// previously added elements.  See php array_merge.
$form->fieldsets	= array('info_fieldset' => $info_fieldset,
							'team_fieldset' => $team_fieldset,
							'account_fieldset' => $account_fieldset,
							'database_fieldset' => $database_fieldset,
							'project_fieldset' => $project_fieldset);

$form->print_html();
>>>>>>> bfc033b796e31ff5415d21d639b6f0c86912d183
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