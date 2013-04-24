<?php
require_once 'JAAMSForms_Base.class.php';

class JAAMSForms_Fieldset extends JAAMSForms_Base
{
	// PROPERTIES
	// - PROTECTED
	protected $groups					= array();
	protected $fieldsets				= array();
	protected $inputs					= array();
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
	public function __construct( $name, array $dir_paths = array() ) {
		// Instantiate JAAMSTemplatable parent.
		parent::__construct($name, $dir_paths);
		$this->hierarchies = array(
			'view'		=> array('default', 'fieldset'),
		);
	}
	 
	// - PROTECTED
}
		
