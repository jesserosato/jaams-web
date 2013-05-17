<?php
namespace Forms\Controllers;
require_once 'Base.class.php';

class Form extends Base
{
	// PROPERTIES
	// - PROTECTED
	public $fieldsets				= array();
	public $groups					= array();
	public $inputs					= array();
	public $atts					= array(
		'method'		=> 'post',
		'action'		=> '',
		'enctype'		=> 'multipart/form-data'
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
		// Instantiate Base parent.
		parent::__construct($name, $dir_paths);
		// Set the template hierarchy.
		$this->hierarchies['view'] = array('default', 'form');
	}
	
	/**
	 * Make raw data safe for HTML display
	 */
	public function sanitize( ) {
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
	public function validate( ) {
		$this->_validate();
		$data_global = $this->_data_global_name();
		foreach ( $this->fieldsets as &$fieldset ) {
		    $fieldset->validate($data_global);
		    if ( ! empty ( $fieldset->errors ) ) {
		   	 $this->errors[$fieldset->name] = $fieldset->errors;
		    }
		}
		foreach ( $this->groups as &$group ) {
		    $group->validate($data_global);
		    if ( ! empty ( $group->errors ) ) {
		   	 $this->errors[$group->name] = $group->errors;
		    }
		}
		foreach ( $this->inputs as &$input ) {
		    $input->validate($data_global);
		    if ( ! empty ( $input->errors ) ) {
		   	 $this->errors[$input->name] = $input->errors;
		    }
		}
		return empty ( $this->errors );
	}
	 
	
	/**
	 * save function.
	 * 
	 * @access public
	 * @return void
	 */
	public function save( ) {
		if ( empty ( $this->model ) ) {
			$db_info = empty($this->args['db_info']) ? array() : $this->args['db_info'];
			$this->model = new \Forms\Models\Base($this, $db_info);
		}
		return $this->model->save();
	}
	 
	 // - PROTECTED
	 
	 /**
	  * Return the name of the global data array or object containing the form data.
	  *
	  */
	 protected function _data_global_name( ) {
		 return empty($this->atts['method']) ? 'POST' : strtoupper($this->atts['method']);
	 }
	 
	 // Basically abstract.
	 protected function _validate() {
	 }
}
		
