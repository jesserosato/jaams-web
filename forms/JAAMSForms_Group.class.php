<?php
require_once 'JAAMSForms_Base.class.php';

class JAAMSForms_Group extends JAAMSForms_Base
{
	// PROPERTIES
	// - PROTECTED
	protected $inputs					= array();
	
	// METHODS
	// - PUBLIC
	
	/**
	 * __CONSTRUCT
	 *
	 * @param	$name				String	HTML form tag “name” attribute. Used internally as a handle for the form.
	 * @param	$template_paths		Array	Array of paths to template directories, in desired search order.
	 *
	 */
	public function __construct( $name, array $template_dir_paths = array() ) {
		// Instantiate JAAMSTemplatable parent.
		parent::__construct($name);
		$this->template_hierarchy = array('default', 'group');
		
	}
	 
	// - PROTECTED
}
		
