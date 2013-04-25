<?php
namespace Forms\Models;
// Make sure the base controller has been defined and load the base model (see 'core/JAAMSBase.class.php').
require_once(\JAAMS\ROOT.'/core/controllers/Base.class.php');
require_once(\JAAMS\ROOT.'/core/models/Base.model.php');

class Base extends \JAAMS\Core\Models\Base {
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