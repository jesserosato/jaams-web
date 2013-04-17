<?php
// Root directory.  Very important.  Do not change this unless you know exactly what you're doing.
define('JAAMS_ROOT', dirname(__FILE__));
define('JAAMS_DIR_SEP', '/');

// Debugging information
@ini_set('display_errors',1);
define('JAAMS_DEBUG', true);
define('JAAMS_DEBUGGER_LOG', JAAMS_ROOT.'/logs/debug.log');

// Database connection info
define('JAAMS_DB_USER', 'root');
define('JAAMS_DB_HOST', 'localhost');
define('JAAMS_DB_PASSWORD', '');
define('JAAMS_DB_NAME', '');