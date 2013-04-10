<?php
// No autoloading for PHP4 compatibility
require_once 'JAAMSForms_Base.class.php';

class JAAMSForms_Form extends JAAMSForms_Base
{
	// PROPERTIES
	// - PROTECTED
	protected $fieldsets				= array();
	protected $groups					= array();
	protected $inputs					= array();
	protected $atts						= array(
		'method'		=> 'post',
		'action'		=> '',
		'enctype'		=> 'text/plain',
		'autocomplete'	=> 'on',
	);
	
	
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
		// Instantiate JAAMSForms_Base parent.
		parent::__construct($name);
		// Set the template hierarchy.
		$this->template_hierarchy = array('default', 'form');
	}
}
		
