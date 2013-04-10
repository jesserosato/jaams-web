<?php
// Include the JAAMS templatable class from core.
require_once JAAMS_ROOT . '/core/JAAMSTemplatable.class.php';

// Define default template path.
define('JAAMS_DEF_TEMPLATE_DIR_PATH', dirname(__FILE__).'/default_templates');

class JAAMSForms_Base extends JAAMSTemplatable
{
	// PROPERTIES
	// - PUBLIC
	// - PROTECTED
	protected $name;
	protected $label					= '';
	protected $errors					= array();
	protected $template_dir_paths		= array(JAAMS_DEF_TEMPLATE_DIR_PATH);
	
	
	
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
		// Instantiate JAAMSTemplatable parent with default template dir path 
		// appended to the provided dir paths array.
		parent::__construct(array_merge($template_dir_paths, $this->template_dir_paths));
		// Initialize data.
		$this->name	= $name;
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
		return $this->get_template(
			$this->template_hierarchy, 
			$this, 
			$this->template_ext, 
			$this->template_sep
		);
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
		 $data['atts'] = array();
		 // - Name
		 $data['atts'][] = 'name="' . $data['name'] . '"';
		 foreach( $this->atts as $att => $val ) {
			 $data['atts'][] = $att . '="' . $val . '"';
		 }
		 $data['atts'] = implode(' ', $data['atts']);
		 
		 return $data;
	 }
	 
	// - PROTECTED
}
		
