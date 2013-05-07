<?php
namespace Forms\Controllers;
require_once 'Base.class.php';
require_once 'FormElement.class.php';

/**
 * A sort of enum to describe input types.
 */
class InputTypes {
	const text			= 0;
	const textarea		= 1;
	const select		= 2;
	const submit		= 3;
	const button		= 4;
	const checkbox		= 5;
	const checkboxes	= 6;
	const radios		= 7;
}

class InputValidators {
	// TODO: build default, BOOLEAN-RETURNING validation functions that are statically callable.
	public static function email( $email ) {
		// filter_var is a cool php function that lets you easily check a variable
		// against any one of a bunch of a predefined constants.
		// google filter_var php for more info (duh). 
		return ( bool ) filter_var( $email, FILTER_VALIDATE_EMAIL );
	}

    public static function only_letters( $value ){


        return preg_match( "/^[a-zA-Z]/", $value);
    }
    public static function greater_zero( $value ){
    
        return ((int) $value) > 0;
    }
    public static function phone ( $phone ){

		$new_phone = preg_replace("/[^0-9xX]/", '', $phone);

        return preg_match( "/^[1]?[0-9]{10}([xX][0-9]{1-4})?$/", $new_phone);
    }

	/**
	 * Compare two values, return true if they are not EXACTLY (type too) equal.
	 *
	 * 
	 */
	public static function not( $value, $not ) {
		return ! $value === $not;
	}
}

class Input extends Base implements FormElement
{
	// PROPERTIES
	// - PROTECTED
	protected $type						= InputTypes::text;
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
	
	
	/**
	 * set_raw_value function.
	 * 
	 * @access public
	 * @param string $data_global (default: 'POST')
	 * @return void
	 */
	public function set_raw( $data_global = 'POST' ) {
		$data = $this->_get_raw( $data_global, $this->name );
		if ( $data ) {
			$this->value = $data;
		}
	}
	
	
	/**
	 * sanitize function.
	 *
	 * Make raw data safe for HTML display
	 *
	 * @access public
	 * @param string $data_global (default: 'POST')
	 * @return void
	 */
	public function sanitize( $data_global = 'POST' ) {
		if ( empty( $this->value ) )
			$this->set_raw( $data_global );
		// Set the filter flag to sanitize appropriately.
		$flag = preg_match('/email/i', $this->name) ? FILTER_SANITIZE_EMAIL : FILTER_SANITIZE_STRING;
		// If the value is an array, sanitize each value.
		if ( is_array( $this->value ) ) {
			foreach ( $this->value as $key => $value ) {
				$this->value[$key] = filter_var($this->value[$key], $flag);
			}
		} else {
			$this->value = filter_var($this->value, $flag);
		}
	}
	
	
	/**
	 * validate function.
	 * 
	 * Validate an input's data, set appropriate error.
	 *
	 * @access public
	 * @return void
	 */
	public function validate( $data_global = 'POST' ) {
		
		if ( empty( $this->args['validator'] ) )
			return true;
		if ( empty( $this->value ) )
			$this->set_raw( $data_global );
			
		$validator = $this->args['validator'];
		if ( is_array( $validator ) ) {
			foreach ( $validator as $function ) {
				if ( ! $this->_validate( $function ) ) {
					$this->errors[$validator] = $this->_error($validator);
				}
			}
		} else {
			// We only want to set anything at all here if there is an error.
			if ( ! $this->_validate( $validator ) ) {
				$this->errors[$validator] = $this->_error($validator);
			}
		}
		
		return empty( $this->errors );
	}
	 
	// - PROTECTED
	
	/**
	 * _validate function.
	 * 
	 * @access protected
	 * @param mixed $function
	 * @return void
	 */
	protected function _validate( $function ) {
		switch ( $function ) {
			case 'email':
				// Simple example.
				return InputValidators::email( $this->value );
			case 'only_letters':
				return InputValidators::only_letters($this->value);
			case 'greater_zero':
				return InputValidators::greater_zero($this->value);
			case 'phone':
				return InputValidators::phone($this->value);
			case 'not_default':
				// More complicated example.
				// If no default value is provided, the element is ok if the value's not empty.
				if ( empty( $this->args['default_value'] ) )
					return ! empty( $this->value );
				// Otherwise, just make sure the user's value and the default value are different.
				return InputValidators::not( $this->value, $this->args['default_value']);
			default:
				return true;
		}
	}
	
	
	/**
	 * _get_raw function.
	 * 
	 * @access protected
	 * @param mixed $superglobal
	 * @param mixed $key
	 * @return void
	 */
	protected function _get_raw( $superglobal, $key ) {
		$superglobal = trim(strtoupper($superglobal));
		switch ( $superglobal ) {
			case 'POST' :
				return empty($_POST[$key]) ? false : $_POST[$key];
			break;
			case 'GET' :
				return empty($_GET[$key]) ? false : $_GET[$key];
		}
	}
}
