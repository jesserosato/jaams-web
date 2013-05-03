<?php
namespace JAAMS;
// Root directory.  Very important.  Do not change this unless you know exactly what you're doing.
define(__NAMESPACE__.'\ROOT', dirname(__FILE__));
define(__NAMESPACE__.'\APP_ROOT', dirname(__FILE__).'\application');

// Debugging information
@ini_set('display_errors',1);
define(__NAMESPACE__.'\DEBUG', true);
define(__NAMESPACE__.'\DEBUG_LOG', ROOT.'/logs/debug.log');

// Database connection\ info
define(__NAMESPACE__.'\DB_DRIVER', 'mysql');
define(__NAMESPACE__.'\DB_USER', 'root');
define(__NAMESPACE__.'\DB_HOST', 'localhost');
define(__NAMESPACE__.'\DB_PASSWORD', '');
define(__NAMESPACE__.'\DB_NAME'	, '');
define(__NAMESPACE__.'\DB_PORT'	, false);
define(__NAMESPACE__.'\DB_SOCKET'	, false);