<?php
// Make sure the base controller has been defined and load the base model (see 'core/JAAMSBase.class.php').
require_once(JAAMS_ROOT.'/core/JAAMSBase.class.php');

class JAAMSForms_Model extends JAAMSBase_Model {
	// PROPERTIES
	// - PROTECTED
	protected $_controller;
	
	// METHODS
	// - PUBLIC
	
	/**
	 * CONSTRUCTOR
	 *
	 * @param	$controller		JAAMSBase
	 */
	public function __construct( JAAMSBase $controller ) {
		$this->_controller	= $controller;
	}
	// - PROTECTED
}