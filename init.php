<?php
namespace JAAMS\Core\Utilities;
require_once(dirname(__FILE__).'/config.php');
// Manually load our utility functions (because require_files is one of them).
require_once(dirname(__FILE__).'/core/utilities/functions.php');

// Get all the subdirectories of JAAMS\ROOT
$modules = glob('*', GLOB_ONLYDIR);
// Load core init
require_once(dirname(__FILE__).'/core/init.php');
// Load all the other files ), except the application.
foreach ( $modules as $dir ) {
	if ( $dir != 'core' && $dir != 'application' ) {
		if ( is_readable( dirname(__FILE__).'/'.$dir .'/init.php' ) ) {
			require_once(dirname(__FILE__).'/'.$dir .'/init.php');
		}
	}
}
// Load the applications files last.
require_once(dirname(__FILE__).'/application/init.php');
if ( \JAAMS\DEBUG ) {
	$GLOBALS['JAAMS']['DEBUGGER'] = new Debugger(\JAAMS\DEBUG_LOG);
}