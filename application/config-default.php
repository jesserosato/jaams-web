<?php
namespace \Application;

// Site admin email
define(__NAMESPACE__.'\SITE_ADMIN_EMAIL', 'putarealemailaddresshere@example.com');
define(__NAMESPACE__.'\SITE_ADMIN_EMAIL_FROM_NAME', 'Site Admin');

// Database connection info
define(__NAMESPACE__.'\DB_DRIVER', 'mysql');
define(__NAMESPACE__.'\DB_USER', 'root');
define(__NAMESPACE__.'\DB_HOST', 'localhost');
define(__NAMESPACE__.'\DB_PASSWORD', '');
define(__NAMESPACE__.'\DB_NAME'	, '');
define(__NAMESPACE__.'\DB_PORT'	, false);
define(__NAMESPACE__.'\DB_SOCKET'	, false);

// SMTP connection info
define(__NAMESPACE__.'\SMTP_HOST', '');
define(__NAMESPACE__.'\SMTP_AUTH', true);
define(__NAMESPACE__.'\SMTP_USERNAME', '');
define(__NAMESPACE__.'\SMTP_PASSWORD', '');

// SSH connection info
define(__NAMESPACE__.'\SSH_USER', '');
define(__NAMESPACE__.'\SSH_PASSWORD', '');

// Request-form defaults
define(__NAMESPACE__.'\MAX_PARTICIPANTS', 10);
define(__NAMESPACE__.'\SPRING_START', 'Y-01-01');
define(__NAMESPACE__.'\SPRING_END', 'Y-06-31'); 
define(__NAMESPACE__.'\FALL_START', 'Y-07-01');
define(__NAMESPACE__.'\FALL_END', 'Y-12-31');
define(__NAMESPACE__.'\SEMESTER_LENGTH', 6);
define(__NAMESPACE__.'\DEFAULT_SEMESTERS_ACTIVE', 1);

// PUT ADMIN EMAIL ADDRESSES HERE!
class EmailAddresses {
	public static $admin		= array(
		'firstadminemailaddress@example.com',
		'secondadminemailaddress@example.com',
	);
}