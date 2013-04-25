<?php
namespace JAAMS\Core\Utilities;
require_once('config.php');
// Manually load our utility functions (because require_files is one of them.
require_once('core/utilities/functions.php');

// Get all the subdirectories of JAAMS\ROOT
$modules = glob('*', GLOB_ONLYDIR);
// Load core files.
require_files(dirname(__FILE__).'/core', '/(init|functions|.class|.model)\.php$/', true);
// Load all the other files.
foreach ( $modules as $dir ) {
	if ( $dir != 'core' ) {
		require_files(dirname(__FILE__).'/'.$dir, '/(init|functions|.class|.model)\.php$/', true);
	}
}
if ( \JAAMS\DEBUG ) {
	$GLOBALS['JAAMS']['DEBUGGER'] = new Debugger(\JAAMS\DEBUG_LOG);
}