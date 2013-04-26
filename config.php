<?php
namespace JAAMS;
// Root directory.  Very important.  Do not change this unless you know exactly what you're doing.
define(__NAMESPACE__.'\ROOT', dirname(__FILE__));

// Debugging information
@ini_set('display_errors',1);
define(__NAMESPACE__.'\DEBUG', true);
define(__NAMESPACE__.'\DEBUG_LOG', ROOT.'/logs/debug.log');

// Database connection\ info
define(__NAMESPACE__.'\DB_USER', 'root');
define(__NAMESPACE__.'\DB_HOST', 'localhost');
define(__NAMESPACE__.'\DB_PASSWORD', '');
define(__NAMESPACE__.'\DB_NAME'	, 'dataman');
define(__NAMESPACE__.'\DB_PORT'	, false);
define(__NAMESPACE__.'\DB_SOCKET'	, false);