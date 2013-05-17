<?php
namespace Forms\Controllers;
require_once 'Base.class.php';
require_once 'FormElement.class.php';

/**
 * Fieldset class.
 * 
 * @extends Base
 * @implements FormElement
 */
class Fieldset extends Base implements FormElement
{
	// PROPERTIES
	// - PROTECTED
	public $fieldsets				= array();
	public $groups					= array();
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
			'view'		=> array('default', 'fieldset'),
		);
	}
	
	/**
	 * Make raw data safe for HTML display
	 *
	 * @param $data_global String String containing the name of a global variable containing data.
	 */
	public function sanitize( $data_global = 'POST' ) {
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
	 * Validate a fieldset's data, set appropriate errors.
	 *
	 * @return bool
	 *
	 */
	public function validate($data_global = 'POST') {
		$this->_validate();
		foreach ( $this->fieldsets as &$fieldset ) {
		    $fieldset->validate($data_global);
		    if ( ! empty ( $fieldset->errors ) ) {
		   	 $this->errors[$fieldset->name] = $fieldset->errors;
		    }
		}
		foreach ( $this->groups as &$group ) {
		    $group->validate($data_global);
		    if ( ! empty ( $group->errors ) ) {
		   	 $this->errors[$group->name] 	= $group->errors;
		    }
		}
		foreach ( $this->inputs as &$input ) {
		    $input->validate($data_global);
		    if ( ! empty ( $input->errors ) ) {
		   	 $this->errors[$input->name] 	= $input->errors;
		    }
		}
		return empty ( $this->errors );
		 
	 }
	 
	 protected function _validate() {
		if ( empty( $this->args['validator'] ) )
			return;
			
		$validator = $this->args['validator'];
		if ( is_array( $validator ) ) {
			foreach ( $validator as $function ) {
				if ( ! $this->_call_validator( $function ) ) {
					$this->errors[$function] = $this->_error($function);
				}
			}
		} else {
			// We only want to set anything at all here if there is an error.
			if ( ! $this->_call_validator( $validator ) ) {
				$this->errors[$validator] = $this->_error($validator);
			}
		}
		
		return empty( $this->errors );
	 }
	 
	 // "Abstract" function
	 protected function _call_validator( $function ) {
		return true;
	}
}