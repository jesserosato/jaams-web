<?php try { ?>
<?php require_once 'init.php'; ?>
<head>
	<title>Request a Project and/or MySQL Database Account</title>
	<?php // TODO: Create "add_css" and "add_js" methods, load CSS that way. ?>
	<link rel="stylesheet" href="css/style.css" />
</head>
<body>
	<?php include \JAAMS\ROOT.'/index.php'; ?>
</body>
<?php } catch( \Exception $e ) {
	if ( ! empty( $GLOBALS['JAAMS']['DEBUGGER'] ) ) {
		$GLOBALS['JAAMS']['DEBUGGER']->debug_log($e->getMessage());
	}
}