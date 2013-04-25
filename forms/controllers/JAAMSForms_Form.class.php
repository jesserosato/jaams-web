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
	public function __construct( $name, array $dir_paths = array() ) {
		// Instantiate JAAMSForms_Base parent.
		parent::__construct($name, $dir_paths);
		// Set the template hierarchy.
		$this->hierarchies = array(
			'view'		=> array('default', 'form'),
		);
	}
	
	/**
	 * Get raw data.
	 *
	 */
	public function set_raw_values() {
		$data_global = $this->_data_global_name();
		foreach ( $this->fieldsets as &$fieldset ) {
			$fieldset->set_raw_values($data_global);
		}
		foreach ( $this->groups as &$group ) {
			$group->set_raw_values($data_global);
		}
		foreach ( $this->inputs as &$input ) {
			$input->set_raw_value($data_global);
		}
	}
	
	/**
	 * Make raw data safe for HTML display
	 */
	public function sanitize() {
		$data_global = $this->_data_global_name();
		foreach ( $this->fieldsets as &$fieldset ) {
			$fieldset->sanitize($data_global);
		}
		foreach ( $this->groups as &$group ) {
			$group->sanitize($data_global);
		}
		foreach ( $this->inputs as &$input ) {
			$input->sanitize($data_global);
		}
	}
	
	/**
	 * Validate a form's data, set appropriate errors.
	 *
	 * @return bool
	 *
	 */
	 public function validate() {
		 foreach ( $this->fieldsets as &$fieldset ) {
			 $fieldset->validate();
			 if ( ! empty ( $fieldset->errors ) ) {
				 $this->errors[$fieldset->name] = $fieldset->errors;
			 }
		 }
		 foreach ( $this->groups as &$group ) {
			 $group->validate();
			 if ( ! empty ( $group->errors ) ) {
				 $this->errors[$group->name] = $group->errors;
			 }
		 }
		 foreach ( $this->inputs as &$input ) {
			 $input->validate();
			 if ( ! empty ( $input->errors ) ) {
				 $this->errors[$input->name] = $input->errors;
			 }
		 }
		 return empty ( $this->errors );
		 
	 }
	 
	 // - PROTECTED
	 
	 /**
	  * Return the name of the global data array or object containing the form data.
	  *
	  */
	 protected function _data_global_name() {
		 return empty($this->atts['method']) ? 'POST' : strtoupper($this->atts['method']);
	 }
}
		
