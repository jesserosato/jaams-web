<?php
namespace Forms\Controllers;
// Include the JAAMS base class from core.
require_once \JAAMS\ROOT . '/core/controllers/Base.class.php';

use \Forms\VIEWS_DIR_PATH as VIEWS_DIR_PATH;
use \Forms\MODELS_DIR_PATH as MODELS_DIR_PATH;

class Base extends \JAAMS\Core\Controllers\Base {
	// PROPERTIES
	// - PUBLIC
	// - PROTECTED
	protected $name;
	public $label					= '';
	public $args					= array();
	protected $errors				= array();
	
	// METHODS
	// - PUBLIC
	
	/**
	 * __CONSTRUCT
	 *
	 * @access	public
	 * @param	$name				String	HTML form tag “name” attribute. Used internally as a handle for the form.
	 * @param	$dir_paths			Array	Array of paths to view and model directories, in desired search order.
	 *
	 */
	public function __construct( $name, array $dir_paths = array() ) {
		// Set up the directory paths
		if ( empty( $dir_paths['view'] ) || ! is_array( $dir_paths['view'] ) ) {
			$dir_paths['view']	= array(VIEWS_DIR_PATH);
		} else {
			array_push($dir_paths['view'], VIEWS_DIR_PATH);
		}
		if ( empty( $dir_paths['model'] ) || ! is_array($dir_paths['model'] ) ) {
			$dir_paths['model']	= array(MODELS_DIR_PATH);
		} else {
			array_push($dir_paths['model'], MODELS_DIR_PATH);
		}
		// Instantiate parent.
		parent::__construct($dir_paths);
		// Set the default model file hierarchy.
		$this->hierarchies['model']	= array('Base');
		// Set the default model file extension.
		$this->exts['model']		= 'model.php';
		// Set the default view file extension
		$this->exts['view']			= 'template.php';
		// Initialize data.
		$this->name					= $name;
	}
	
	
	/**
	 * get_name function.
	 * 
	 * @access public
	 * @return void
	 */
	public function get_name() {
		return $this->name;
	}
	
	 /**
	  * print_html function.
	  * Output the form using the object's template-related properties.
	  * 
	  * @access public
	  * @return void
	  */
	 public function print_html() {
		echo $this->get_html();
	}
	

	/**
	 * get_html function.
	 * Return html string using the object's template-related properties.
	 *
	 * @access public
	 * @return string
	 */
	public function get_html() {
		$this->set_view();
		return $this->view;
	}
	
	
	/**
	 * get_template_data function.
	 * 
	 * @access public
	 * @return array
	 */
	public function get_template_data() {
		$data = array();
		foreach( get_class_vars(get_class($this)) as $key => $default ) {
		    $data[$key] = $this->$key;
		}
		// Attributes get converted to HTML strings.
		if ( empty ( $this->atts ) ) {
		   $data['atts'] = '';
		} else {
		   $data['atts'] = array();
		   foreach( $this->atts as $att => $val ) {
		   	$data['atts'][] = $att . '="' . $val . '"';
		   }
		   $data['atts'] = implode(' ', $data['atts']);
		}
		return $data;
	 }
	 
	/**
	 * _error function.
	 * OVERRIDE
	 *
	 * @access protected
	 * @param mixed $validator
	 * @return void
	 */
	protected function _error( $validator ) {
		return empty($this->args['error_msgs'][$validator]) ? "Error of type '$validator'." : $this->args['error_msgs'][$validator];
	}
}
		
