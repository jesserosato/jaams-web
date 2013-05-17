<?php
namespace JAAMS\Core\Controllers;
/**
 * Base Controller Class
 * Note that true abstraction is not supported before PHP5
 */
class Base {
	// PROPERTIES
	// - PROTECTED
	// $model supports any class that takes a JAAMSBase object as first constructor arg.
	protected $model				= null;		
	protected $view					= '';
	public $dir_paths			= array(
		'view'	=> array(),
		'model'	=> array()
	);
	public $hierarchies			= array(
		'view'	=> array('default'),
		'model'	=> array('Base.model')
	);
	public $exts					= array(
		'view'	=> 'php',
		'model'	=> 'php'
	);
	public $seps					= array(
		'view'	=> '-',
		'model'	=> '-'
	);
	// - PRIVATE
	private $_indices				= array(
		'view'	=> -1,
		'model'	=> -1
	);
	
	
	// METHODS
	// - PUBLIC
	
	/**
	 * CONSTRUCTOR
	 *
	 * @param $paths Array of paths to model and view directories, in desired search order.
	 *
	 */
	public function __construct( array $paths ) {
		// Set default model path
		$paths['model'] = empty( $paths['model'] ) ? array(\JAAMS\ROOT.'/core/models') : $paths['model'];
		$this->dir_paths = $paths;
		// Set the view_dir to the first readable directory.
		foreach ( $paths as $component => $component_paths ) {
			$i = 0;
			if ( ! is_array( $component_paths ) ) {
				continue;
			}
			foreach( $component_paths as $path ) {
				if ( ( $this->_indices[$component] < 0 ) &&  opendir($path) ) {
					$this->_indices[$component] = $i;	
				}
				$i++;
			}
		}
		// We don't have a default view directory, make sure the user passes one.
		if ( $this->_indices['view'] < 0 )
			throw new \Exception(get_class($this) . ' expects at least one path to a readable view directory.');
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
	public function __set ( $property , $value ) {
		$funct = 'set_'.$property;
		// Use setter methods where they exist.
		if ( is_callable( array( $this, $funct ) ) )
			$this->$funct($value);
		else
			user_error("Property '$property' cannot be set directly.");
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
	public function &__get ( $property ) {
		if ( ! property_exists( $this, $property ) )
			user_error("Property '$property' doesn't exist in class " . get_class($this) . ".");
		// use the get_ function if it exists.
		if ( empty ( $this->$property ) ) {
			if ( is_callable( array ( $this, ( $funct = 'set_'.$property ) ) ) ) {
				$this->$funct();
			}
		}
		return $this->$property;
	}
	
	
	/**
	 * set_view function.
	 * 
	 * @access public
	 * @return void
	 */
	public function set_view() {
		// Get view filename.
		$filename = $this->get_path();
		// If the filename doesn't exist, leave view unchanged.
		if ( ! $filename )
			return;
		// Get the contents of the view file.
		ob_start();
		include($filename);
		// Set view to the contents of ob_get_contents
		$this->view = ob_get_contents();
		ob_end_clean();
	}
	
	
	/**
	 * set_model function.
	 * NOTICE: YOUR MODEL MUST BE THE FIRST CLASS IN THE FILE!
	 * 
	 * @access public
	 * @param mixed $model (default: null)
	 * @return void
	 */
	public function set_model( $model = null ) {
		if ( $model != null ) {
			$this->model = $model;
			return;
		}
		// Get the path to the model.
		$filename = $this->get_path('model');
		// Can't set the model if we couldn't find it, leave it what it was.
		if ( ! ( $filename ) )
			return;
		// Get the PHP classes that are in the file
		$classes = $this->_file_get_php_classes($filename);
		// Use the first one.
		if ( !empty( $classes ) ) {
			$class_name = $classes[0];
			$this->model = new $class_name($this);
		}
	}
	
	/**
	 * get_path
	 *
	 * Use the search logic for files to find a file path.
	 *
	 * @param	$component	String	The component being searched for: 'view' (default) or 'model'.
	 *
	 * @return	Mixed		A string containing a path to a view file, or false.
	 *
	 */
	public function get_path( $component = 'view' ) {
		// Default to 'view' if $component is not recognized.
		$component = in_array($component, array('view', 'model')) ? $component : 'view';
		$index = $this->_indices[$component];
		while ( ( $index < count( $this->dir_paths[$component] ) ) && empty ( $ret ) ) {
			$ret = $this->_get_path_in_dir($this->dir_paths[$component][$index], $component);
			$index++;
		}
				
		return empty( $ret ) ? false : $ret;
	}
	
	// - PROTECTED
	/**
	 * _get_view_path_in_dir
	 *
	 * Use the search logic for views to return the contents of a view.
	 *
	 * @param	$dir		String		The directory path to search for the view.
	 * @param	$component	String		The component being searched for: 'view' (default) or 'model'.
	 *
	 * @return	Mixed		String containing a path to a readable view file, or false.
	 *
	 */
	protected function _get_path_in_dir( $dir, $component = 'view' ) {
		$order = $this->hierarchies[$component];
		$fileanme = false;
		while ( ! empty( $order ) && ( empty( $filename ) || ! is_readable( $filename ) ) ) {
			$filename = $dir . '/' . implode($this->seps[$component], $order) . '.' . $this->exts[$component];
			array_pop($order);
		}
		
		return is_readable( $filename ) ? $filename : false;
	}
	
	/**
	 * Get the PHP classes in a file.
	 *
	 * Used under license cc-by-sa.
	 * Code referenced from Stack Overflow:
	 * http://stackoverflow.com/questions/928928/determining-what-classes-are-defined-in-a-php-class-file
	 * Author: cletus - http://stackoverflow.com/users/18393/cletus
	 *
	 *
	 * @param	$filename	String
	 *
	 */
	protected function _file_get_php_classes($filename) {
		$php_code = file_get_contents($filename);
		$classes = $this->_get_php_classes($php_code);
		return $classes;
	}
	
	/**
	 * Get the PHP classes in a block of PHP code.
	 *
	 * Used under license cc-by-sa.
	 * Code referenced from Stack Overflow:
	 * http://stackoverflow.com/questions/928928/determining-what-classes-are-defined-in-a-php-class-file
	 * Author: cletus - http://stackoverflow.com/users/18393/cletus
	 *
	 *
	 * @param	$filename	String
	 *
	 */
	 protected function _get_php_classes($php_code) {
	 	$classes = array();
	 	if ( empty ( $count ) ) {
	 	    $tokens = token_get_all($php_code);
	 	    $count = count($tokens);
	 	}
	 	for ($i = 2; $i < $count; $i++) {
	 		if (   $tokens[$i - 2][0] == T_NAMESPACE
	 			&& $tokens[$i - 1][0] == T_WHITESPACE
	 			&& $tokens[$i][0] == T_STRING) {
	 			$namespace = $this->_get_php_namespace($tokens, $i);
	 		}
	 		if (   $tokens[$i - 2][0] == T_CLASS
	 			&& $tokens[$i - 1][0] == T_WHITESPACE
	 			&& $tokens[$i][0] == T_STRING) {
	 			$class_name = $tokens[$i][1];
	 			$classes[] = $namespace.'\\'.$class_name;
	 		}
	 	}
	 	return $classes;
	 }
	
	protected function _get_php_namespace($tokens, $i = 2) {
		$namespace_parts[] = $tokens[$i][1];
		$i += 2;
		while ( $tokens[$i-1][0] == T_NS_SEPARATOR ) {
			$namespace_parts[] = $tokens[$i][1];
			$i += 2;
		}
		return implode('\\', $namespace_parts);
	}
	
}