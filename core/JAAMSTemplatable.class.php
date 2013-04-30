<?php

/**
 * "Abstract" Templatable Class
 * Note that true abstraction is not supported before PHP5
 */
class JAAMSTemplatable {
	// PROPERTIES
	// - PROTECTED
	protected $template_dir_paths		= array();
	protected $template_hierarchy		= array();
	protected $template_ext				= 'php';
	protected $template_sep				= '-';
	// - PRIVATE
	private $_index;
	
	// METHODS
	// - PUBLIC
	
	/**
	 * CONSTRUCTOR
	 *
	 * @param $path Array of paths to directories, in desired search order.
	 *
	 */
	public function __construct( array $paths ) {
		$this->template_dir_paths = $paths;
		$this->_index = -1;
		$i = 0;
		// Set the template_dir to the first readable directory.
		foreach ( $paths as $path ) {
			if ( ( $this->_index < 0 ) &&  @opendir($path) ) {
				$this->_index = $i;	
			}
			$i++;
		}
		// We need at least one readable dir.
		if ( $this->_index < 0 )
			throw new Exception('JAAMSTemplatable expects at least one path to a readable directory.');
	}
	
	/**
	 * __SET
	 *
	 * PHP auto magic setter method.
	 *
	 * @param	$prop	String
	 * @param	$value	Mixed
	 *
	 */
	public function __set ( $prop , $value ) {
		$defaults = get_class_vars(get_class($this));
		// Make sure the new value is of the same type as the default value.
		if ( gettype( $value ) != gettype( $defaults[$prop] ) )
			return;
		
		$this->$prop = $value;
	}
	
	/**
	 * __GET
	 *
	 * PHP auto magic getter method.
	 *
	 * @param	$prop	String
	 *
	 * @return	Mixed
	 */
	public function __get ( $prop ) {
		return $this->$prop;
	}
	
	/**
	 * get_template_path
	 *
	 * Use the search logic for templates to find a template file path.
	 *
	 * @return	Mixed		A string containing a path to a template file, or false.
	 *
	 */
	public function get_template_path( ) {
		$index = $this->_index;
		while ( ( $index < count( $this->template_dir_paths ) ) && empty ( $ret ) ) {
			$ret = $this->_get_template_path_in_dir( $this->template_dir_paths[$index] );
			$index++;
		}
				
		return empty( $ret ) ? false : $ret;
	}
	
	/**
	 * get_template
	 *
	 * Use the search logic for templates to return the contents of a template.
	 *
	 * @return	String		A string containing the contents of a template file, or an empty string.
	 *
	 */
	public function get_template( ) {
		if ( ! ( $filename = $this->get_template_path( $this->template_hierarchy, $this->template_ext, $this->template_sep ) ) )
			return '';
		
		ob_start();
		include($filename);
		$ret = ob_get_contents();
		ob_end_clean();
		
		return $ret;
	}
	
	// - PROTECTED
	/**
	 * _get_template_path_in_dir
	 *
	 * Use the search logic for templates to return the contents of a template.
	 *
	 * @param	$dir		String		The directory path to search for the template.
	 *
	 * @return	Mixed		String containing a path to a readable template file, or false.
	 *
	 */
	protected function _get_template_path_in_dir( $dir ) {
		$order = $this->template_hierarchy;
		while ( ! empty( $order ) && ( empty( $filename ) || ! is_readable( $filename ) ) ) {
			$filename = $dir . '/' . implode($this->template_sep, $order) . '.' . $this->template_ext;
			array_pop($order);
		}
		
		return empty( $filename ) ? false : $filename;
		
	}
	
	
}