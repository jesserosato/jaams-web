<?php
// AA: If you're working through this and you feel like these need to be able to access the user-supplied data,
//     just let me know and I can add a quick token-replacement system.
//     
//     In index.php, include this file.  Then attach these error messages to the Form\Input objects like:
//			$email_input->args['error_msgs'] = $error_msgs['email_input_name'].

define('ECS_SITE_ADMIN_EMAIL', 'putarealemailaddresshere@example.com');

$error_msgs = array(
	// The first thing is to create keyes-array pair for each element:
	'advisor'		=> array(
		'only_letters'=> 'Numbers are not permitted in this field.',
		'required' => 'The advisor field is required, please provide a valid advisor.'
	),
	'advisor_email'	=> array(
		// Now we add a key-message pair for every validator we're going to attach to the input in index.php.
		'email'		=> 'Please enter a valid email address.',
		'required'	=> 'The Email field is required, please provide a valid email address.'
	),
	'class_no'			=> array(
		'only_numbers' 	=>'Letters are not permitted in this field.',
	),
	'project_name' 	=> array(
		'only_letters'  =>  'Numbers are not permitted in this field.',
        'required'	=> 'The Phone Number field is required, please provide a valid U.S. telephone number.'
    
	),
    'group_name'   		=> array(
        'only_letters'  =>  'Numbers are not permitted in this field.',
        'required'		=> 'The Phone Number field is required, please provide a valid U.S. telephone number.'
    ),
    'indiv_major'		=> array(
        'only_letters'  =>  'Numbers are not permitted in this field.',
        'required'		=> 'The Phone Number field is required, please provide a valid U.S. telephone number.'
    ),
	'first_name'		=> array(
        'only_letters'  =>  'Numbers are not permitted in this field.',
        'required'		=> 'The Phone Number field is required, please provide a valid U.S. telephone number.'
    ),
    'last_name'   		=> array(
        'only_letters'  =>  'Numbers are not permitted in this field.',
        'required'		=> 'The Phone Number field is required, please provide a valid U.S. telephone number.'
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
        'required'	=> 'The Phone Number field is required, please provside a valid U.S. telephone number.'
    ),
    'only_numbers'	=>	array(
    	'letters' 	=> 'Letters are not permitted in this field.',
    ),
	// You can also 
	// Now we'll add the form level errors as key-message pairs. ('database' is actually the only one of these so far).
	'database_connection'	=> 'We were unable to connect to the database.  Please contact the  <a href="mailto:' . ECS_SITE_ADMIN_EMAIL . '">site administrator</a>.',
	'has_ecs_email'	=> "At least one member of your team must provide a valid ECS email address (i.e. one ending in @ecs.csus.edu).",
);
$error_msgs['database_save']	= 'We were unable to save your request to the database.  Please try again, and contact the  <a href="mailto:' . ECS_SITE_ADMIN_EMAIL . '">site administrator</a>. if the problem continues.';