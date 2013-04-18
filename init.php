<?php
require_once('config.php');

global $JAAMS;
$JAAMS = array();

if ( defined('JAAMS_DEBUG') && JAAMS_DEBUG ) {
	require_once(JAAMS_ROOT.'/core/JAAMSDebugger.class.php');
}