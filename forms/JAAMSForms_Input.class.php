<?php
require_once 'JAAMSForms_Base.class.php';

/**
 * A sort of enum to describe input types.
 */
class JAAMSForms_InputTypes {
	const text			= 0;
	const textarea		= 1;
	const select		= 2;
	const submit		= 3;
	const button		= 4;
	const checkbox		= 5;
	const checkboxes	= 6;
	const radios		= 7;
}

class JAAMSForms_Input extends JAAMSForms_Base
{
	// PROPERTIES
	// - PROTECTED
	protected $type						= JAAMSForms_InputTypes::text;
	protected $args						= array();
	protected $atts						= array();
	
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
		$this->template_hierarchy = array('default','inputs');
		
	}
	 
	// - PROTECTED
}
		
