<?php
class JAAMSCollection extends JAAMSBase {
	public function o ( $callable, $args, $iterable = null ) {
		if ( $iterable === null ) {
			$class_vars = get_class_vars(get_class($this));
		}
		foreach ( $class_vars as $prop => $val ) {
			if ( is_array( $val ) || )
		}
	}
}