<?php

/**
 * "Abstract" Controller Class
 * Note that true abstraction is not supported before PHP5
 */
class JAAMSController {
	// PROPERTIES
	// - PROTECTED
	// Model supports any class that takes a JAAMSController object as first constructor arg.
	protected $model				= null;		
	protected $view					= '';
	protected $dir_paths			= array(
		'view'	=> array(),
		'model'	=> array()
	);
	protected $hierarchies			= array(
		'view'	=> array('default'),
		'model'	=> array('JMRModel.class')
	);
	protected $exts					= array(
		'view'	=> 'php',
		'model'	=> 'php'
	);
	protected $seps					= array(
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
		$paths['model'] = empty( $paths['model'] ) ? array(JAAMS_ROOT.'/core') : $paths['model'];
		$this->dir_paths = $paths;
		// Set the view_dir to the first readable directory.
		foreach ( $paths as $component => $component_paths ) {
			$i = 0;
			if ( ! is_array( $component_paths ) ) {
				continue;
			}
			foreach( $component_paths as $path ) {
				if ( ( $this->_indices[$component] < 0 ) &&  @opendir($path) ) {
					$this->_indices[$component] = $i;	
				}
				$i++;
			}
		}
		// We don't have a default view directory, make sure the user passes one.
		if ( $this->_indices['view'] < 0 )
			throw new Exception('JAAMSController expects at least one path to a readable view directory.');
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
		// For loose coupling, let the model be set directly, as otherwise
		// we would need to know something about its type.
		if ( $property === 'model' ) {
			$this->model = $value;
		}
		$defaults = get_class_vars(get_class($this));
		// Make sure the new value is of the same type as the default value.
		if ( gettype( $value ) != gettype( $defaults[$property] ) )
			return;
		
		$this->$property = $value;
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
	public function __get ( $property ) {
		switch ( $property ) {
			case 'view' :
				return $this->get_view();
			break;
			case 'model':
				return $this->get_model();
			break;
			default:
				return $this->$property;
		}
		
	}
	
	/**
	 * get_view
	 *
	 * Use the search logic for views to return the contents of a view.
	 *
	 * @return	String		A string containing the contents of a view file, or an empty string.
	 *
	 */
	public function get_view( $reset = false ) {
		// If we've already created the view, and we're not resetting the view, return the set view.
		if ( ! empty( $this->view ) && ! $reset )
			return $this->view;
		// Get the path to the view file.  If it's empty, return an empty string.
		$filename = $this->get_path(
			$this->hierarchies['view'],
			$this->exts['view'],
			$this->seps['view']
		);
		if ( ! ( $filename ) )
			return ( $this->view  = '' );
		// Get the contents of the view file.s
		ob_start();
		include($filename);
		$this->view = ob_get_contents();
		ob_end_clean();
		
		return $this->view;
	}
	
	/**
	 * get_model
	 *
	 * Use the search logic for models to return the contents of a model.
	 *
	 * @param	$reset		bool	Whether to reload the model if it already exists.
	 *
	 * @return	null		The model object (or null).
	 *
	 */
	public function get_model( $reset = false ) {
		if ( ! empty( $this->model ) && ! $reset )
			return $this->model;
		// Get the path to the model.
		$filename = $this->get_path(
			$this->hierarchies['model'], 
			$this->exts['model'], 
			$this->seps['model'], 
			'model'
		);
		// Can't load the model if we couldn't read any fiels.
		if ( ! ( $filename ) )
			return null;
		// Get the PHP classes that are in the file
		$classes = _file_get_php_classes($filename);
		// Use the first one.
		if ( !empty( $classes ) ) {
			$class_name = $classes[0];
			$this->model = new $class_name($this);
		}
		// Return the loaded model or null.
		return $this->model;
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
		while ( ! empty( $order ) && ( empty( $filename ) || ! is_readable( $filename ) ) ) {
			$filename = $dir . '/' . implode($this->seps[$component], $order) . '.' . $this->exts[$component];
			array_pop($order);
		}
		
		return empty( $filename ) ? false : $filename;
		
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
	  $tokens = token_get_all($php_code);
	  $count = count($tokens);
	  for ($i = 2; $i < $count; $i++) {
	    if (   $tokens[$i - 2][0] == T_CLASS
	        && $tokens[$i - 1][0] == T_WHITESPACE
	        && $tokens[$i][0] == T_STRING) {
	
	        $class_name = $tokens[$i][1];
	        $classes[] = $class_name;
	    }
	  }
	  return $classes;
	}
	
}