<?php
// AA: If you're working through this and you feel like these need to be able to access the user-supplied data,
//     just let me know and I can add a quick token-replacement system.
//     
//     In index.php, include this file.  Then attach these error messages to the Form\Input objects like:
//			$email_input->args['error_msgs'] = $error_msgs['email_input_name'].

$error_msgs = array(
	// The first thing is to create keyes-array pair for each element:
	'advisor'		=> array(
		'only_letters'=> 'Numbers are not permitted in this field.',
		'required' => 'The advisor field is required, please provide a valid advisor.'
	),
	'advisor_email'	=> array(
		// Now we add a key-message pair for every validator we're going to attach to the input in index.php.
		'email'		=> 'Please enter a valid email address.',
		'required'	=> 'The Advisor email field is required, please provide a valid email address.'
	),
	'class_no'			=> array(
		'only_numbers' 	=>'Letters are not permitted in this field.',
		'required'	=> 'The Class number field is required, please provide a valid class number.'
	),
	'project_name' 	=> array(
		'only_letters'  =>  'Numbers are not permitted in this field.',
        'required'	=> 'The Project Name field is required, please provide a valid project name.'
    
	),
    'group_name'   		=> array(
        'only_letters'  =>  'Numbers are not permitted in this field.',
        'required'		=> 'The Phone Number field is required, please provide a valid U.S. telephone number.'
    ),
    'indiv_major'		=> array(
        'only_letters'  =>  'Numbers are not permitted in this field.',
        'required'		=> 'The Major field is required, please provide a valid major.'
    ),
	'first_name'		=> array(
        'only_letters'  =>  'Numbers are not permitted in this field.',
        'required'		=> 'The First Name field is required, please provide a valid first name.'
    ),
    'last_name'   		=> array(
        'only_letters'  =>  'Numbers are not permitted in this field.',
        'required'		=> 'The Last Name field is required, please provide a valid last name.'
    ),
	'email'	=> array(
		// Now we add a key-message pair for every validator we're going to attach to the input in index.php.
		'email'		=> 'Please enter a valid email address.',
		'required'	=> 'The Email field is required, please provide a valid email address.'
	),
	'phone_number'	=> array(
		'phone'		=> 'Please enter a U.S. phone number.',
		'required'	=> 'The Phone Number field is required, please provide a valid U.S. telephone number.'
	),
    'disk_quota'   => array(
        'greater_zero' =>  'Your input must be greater than zero.',
        'required'	=> 'The disk quota field is required, please provside a valid amount.'
    ),
    'only_numbers'	=>	array(
    	'letters' 	=> 'Letters are not permitted in this field.',
    ),
	// You can also 
	// Now we'll add the form level errors as key-message pairs. ('database' is actually the only one of these so far).
	'database_connection'	=> 'We were unable to connect to the database.  Please contact the  <a href="mailto:' . \CSC131\ECS\SITE_ADMIN_EMAIL . '">site administrator</a>.',
	'has_ecs_email'	=> "At least one member of your team must provide a valid ECS email address (i.e. one ending in @ecs.csus.edu).",
	'unique_project_name' => "Sorry, it looks like your chosen project name is already taken.",
);
$error_msgs['database_save']	= 'We were unable to save your request to the database.  Please try again, and contact the  <a href="mailto:' . \CSC131\ECS\SITE_ADMIN_EMAIL . '">site administrator</a>. if the problem continues.';
