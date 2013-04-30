<?php
// AA: If you're working through this and you feel like these need to be able to access the user-supplied data,
//     just let me know and I can add a quick token-replacement system.
//     
//     In index.php, include this file.  Then attach these error messages to the Form\Input objects like:
//			$email_input->args['error_msgs'] = $error_msgs['email_input_name'].

define('ECS_SITE_ADMIN_EMAIL', 'putarealemailaddresshere@example.com');

$error_msgs = array(
	// The first thing is to create keyes-array pair for each element:
<<<<<<< HEAD
	'email'	=> array(
=======
	'email_input_name'	=> array(
>>>>>>> 57359df9d6a55169e4262f7e80bae64210b2ba04
		// Now we add a key-message pair for every validator we're going to attach to the input in index.php.
		'email'		=> 'Please enter a valid email address.',
		'required'	=> 'The Email field is required, please provide a valid email address.'
	),
<<<<<<< HEAD
	'phone_number'	=> array(
		'phone'		=> 'Please enter a U.S. phone number.',
		'required'	=> 'The Phone Number field is required, please provide a valid U.S. telephone number.'
	),
	'first_name'	=> array(
        'numbers'   =>  'Numbers are not permitted in this field.',
        'required'	=> 'The Phone Number field is required, please provide a valid U.S. telephone number.'
    ),
    'only_letters'   => array(
        'numbers'   =>  'Numbers are not permitted in this field.',
        'required'	=> 'The Phone Number field is required, please provide a valid U.S. telephone number.'
    ),
    'greater_zero'   => array(
        'less_zero' =>  'Your input must be greater than zero.',
        'required'	=> 'The Phone Number field is required, please provide a valid U.S. telephone number.'
    ),
    'only_numbers'	=>	array(
    	'letters' 	=> 'Letters are not permitted in this field.',
    ),
	// You can also 
	// Now we'll add the form level errors as key-message pairs. ('database' is actually the only one of these so far).
	'database'	=> 'We were unable to connect to the database.  Please contact the  <a href="mailto:' . ECS_SITE_ADMIN_EMAIL . '">site administrator</a>.'
);

$error_msgs['advisor'] = $error_msgs['only_letters'];
$error_msgs['major'] = $error_msgs['only_letters'];
$error_msgs['project_name'] = $error_msgs['only_letters'];
//$error_msgs['first_name'] = $error_msgs['only_letters'];
$error_msgs['last_name'] = $error_msgs['only_letters'];

$error_msgs['class_no'] = $error_msgs['only_numbers'];

$error_msgs['email'] = $error_msgs['email'];
$error_msgs['advisor_email'] = $error_msgs['email'];

$error_msgs['phone'] = $error_msgs['phone_number'];

$error_msgs['disk_quota'] = $error_msgs['greater_zero'];
=======
	'phone_number_input_name'	=> array(
		'phone'		=> 'Please enter a U.S. phone number.',
		'required'	=> 'The Phone Number field is required, please provide a valid U.S. telephone number.'
	),
	// You can also 
	// Now we'll add the form level errors as key-message pairs. ('database' is actually the only one of these so far).
	'database'	=> 'We were unable to connect to the database.  Please contact the  <a href="mailto:' . ECS_SITE_ADMIN_EMAIL . '">site administrator</a>.'
);
>>>>>>> 57359df9d6a55169e4262f7e80bae64210b2ba04
