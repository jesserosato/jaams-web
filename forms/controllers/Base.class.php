<?php
namespace Forms;

// Include the JAAMS base class from core.
require_once \JAAMS\ROOT . '/core/controllers/Base.class.php';

// Define default template path.
define(__NAMESPACE__.'\VIEWS_DIR_PATH', ROOT.'/default_templates');
define('MODELS_DIR_PATH', ROOT.'/models');

class Base extends \JAAMS\Core\Controllers\Base {
	// PROPERTIES
	// - PUBLIC
	// - PROTECTED
	protected $name;
	protected $label					= '';
	protected $errors					= array();
	
	// METHODS
	// - PUBLIC
	
	/**
	 * __CONSTRUCT
	 *
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
		$this->hierarchies['model']	= array('JAAMSForms');
		// Set the default model file extension.
		$this->exts['model']		= 'model.php';
		// Set the default view file extension
		$this->exts['view']			= 'template.php';
		// Initialize data.
		$this->name					= $name;
	}
	
	/**
	 * Output the form using the object's template-related properties.
	 *
	 */
	 public function print_html() {
		echo $this->get_html();
	}
	
	/**
	 * Return the form using the object's template-related properties.
	 *
	 */
	public function get_html() {
		return $this->get_view();
	}
	
	/**
	 * "Abstract" method to sanitize form input data.
	 *
	 */
	public function sanitize() {
		
	}
	
	/**
	 * "Abstract" method to validate form input data.
	 *
	 */
	public function validate() {
	}
	
	/**
	 * "Abstract" method to save form input data.
	 *
	 */
	public function save() {
		// Probably something like: return $this->model->save();
	}
	
	/**
	 * Return an array of data relating to this form ready to be used in an HTML template.
	 *
	 * @return Array
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
}
		
