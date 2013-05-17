<?php
namespace Forms\Controllers;
require_once 'Base.class.php';
require_once 'FormElement.class.php';

class Group extends Base implements FormElement
{
	// PROPERTIES
	// - PROTECTED
	public $inputs					= array();
	public $atts					= array();
	
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
			'view'		=> array('default', 'group'),
		);
	}
	 
	 /**
	 * Make raw data safe for HTML display
	 *
	 * @param $data_global String String containing the name of a global variable containing data.
	 *
	 */
	public function sanitize( $data_global = 'POST' ) {
		foreach ( $this->inputs as &$input ) {
			$input->sanitize( $data_global );
		}
	}
	
	 /**
	  * validate function.
	  * 
	  * Validate a fieldset's data, set appropriate errors.
	  *
	  * @access public
	  * @param bool $hide_individual_errors (default: true)
	  * @return void
	  */
	 public function validate( $data_global = 'POST' ) {
		 foreach ( $this->inputs as &$input ) {
			 $input->validate($data_global);
			 if ( ! empty ( $input->errors ) ) {
				 $this->errors[$input->name] = $input->errors;
			 }
		 }
		 
		 return empty ( $this->errors );
		 
	 }
}
		
