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

class JAAMSForms_InputValidators {
	// TODO: build default, BOOLEAN-RETURNING validation functions that are statically callable.
	public static function email( $email ) {
		// filter_var is a cool php function that lets you easily check a variable
		// against any one of a bunch of a predefined constants.
		// google filter_var php for more info (duh).
		return filter_var( $this->data['donor']['email'], FILTER_VALIDATE_EMAIL );
	}
	
	public static function 
	
	/**
	 * Compare two values, return true if they are not EXACTLY (type too) equal.
	 *
	 * 
	 */
	public static function not( $value, $not ) {
		return ! $value === $not;
	}
}

class JAAMSForms_Input extends JAAMSForms_Base
{
	// PROPERTIES
	// - PROTECTED
	protected $type						= JAAMSForms_InputTypes::text;
	protected $value					= '';
	protected $atts						= array();
	protected $args						= array();
	
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
			'view'		=> array('default','inputs'),
		);
		
	}
	
	public function sanitize() {
	}
	
	/**
	 * Validate an input's data, set appropriate error.
	 *
	 * @return bool
	 *
	 */
	public function validate( ) {
		
		if ( empty( $this->args['validator'] ) )
			return true;
			
		$validator = $this->args['validator'];
		if ( is_array( $validator ) ) {
			foreach ( $validator as $function ) {
				if ( ! $this->_validate( $function ) ) {
					$this->errors[$function] = true;
				}
			}
		} else {
			if ( ! $this->_validate( $validator ) ) {
				$this->errors[$validator] = true;
			}
		}
		
		return empty( $this->errors );
	}
	 
	// - PROTECTED
	protected function _validate( $function ) {
		switch ( $function ) {
			case 'email':
				// Simple example.
				return JAAMSForms_InputValidators::email( $this->value );
			case 'not_default':
				// More complicated example.
				// If no default value is provided, the element is ok if the value's not empty.
				if ( empty( $this->args['default_value'] ) )
					return ! empty( $this->value );
				// Otherwise, just make sure the user's value and the default value are different.
				return JAAMSForms_InputValidators::not( $this->value, $this->args['default_value']);
			default:
				
		}
	}
}
