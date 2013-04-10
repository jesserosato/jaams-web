<?php
define('JAAMS_ROOT', dirname(__FILE__));

define('JAAMS_DEBUG', true);
define('JAAMS_DEBUG_LOG', JAAMS_ROOT.'/logs/debug.log');

global $JAAMS;
$JAAMS = array();

if ( defined('JAAMS_DEBUG') && JAAMS_DEBUG ) {
	require_once(JAAMS_ROOT.'/core/JAAMSDebugger.class.php');
	global $jaams_debugger;
	$jaams_debugger = new JAAMSDebugger(JAAMS_DEBUG_LOG);
}