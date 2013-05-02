<?php
namespace Forms\Models;

// Make sure Forms\Form has been included, and load the base model (see 'core/JAAMSBase.class.php').
require_once(\Forms\ROOT.'/controllers/Form.class.php');
require_once(\JAAMS\ROOT.'/core/models/Base.model.php');

class Base extends \JAAMS\Core\Models\Base {
	// PROPERTIES
	// - PROTECTED
	protected $ignore_regexps = array('/submit/');

	// METHODS
	// - PUBLIC
	
	/**
	 * set_data function.
	 * 
	 * @access public
	 * @param array $data (default: array())
	 * @return void
	 */
	public function set_data( array $data = array() ) {
		if ( ! empty( $data ) ) {
			$this->data = $data;
			return;
		}
		foreach ( $this->_controller->fieldsets as $fieldset ) {
			$this->_flatten_fieldset_data($fieldset);
		}
		foreach( $this->_controller->groups as $group ) {
			$this->_flatten_group_data($group);
		}
		$this->_flatten_inputs_data($this->_controller->inputs);
	}
	
	public function save() {
		// Try to use $this->data to avoid re-iterating over controller's stuff.
		$this->set_data($this->data);
		
		$columns = implode(', ', \array_keys($this->data));
		$values = implode("', '", $this->data);
		$query = "INSERT into " . $this->db_info['table'] . " ($columns) VALUES ('$values')";

		if ( $result = $this->dbh->query($query) ) {
			return $result;
		} else {
			throw new \Exception("Error querying the database in ". get_class() . ".");
		}
		
	}
	// - PROTECTED
	
	
	/**
	 * _flatten_fieldset_data function.
	 * 
	 * @access protected
	 * @param Controllers\Fieldset $fieldset
	 * @return void
	 */
	protected function _flatten_fieldset_data( \Forms\Controllers\Fieldset $fieldset ) {
		if ( is_array( $fieldset->fieldsets ) ) {
			foreach ( $fieldset->fieldsets as $fieldset )
				$this->_flatten_fieldset_data($fieldset);
		}
		foreach ( $fieldset->groups as $group ) {
			$this->_flatten_group_data($group);
		}
		$this->_flatten_inputs_data($fieldset->inputs);
	}
	
	/**
	 * _flatten_group_data function.
	 * 
	 * @access protected
	 * @param Controllers\Group $group
	 * @return void
	 */
	protected function _flatten_group_data( \Forms\Controllers\Group $group ) {
		$this->_flatten_inputs_data($group->inputs);
	}
	
	/**
	 * _flatten_inputs_data function.
	 * 
	 * @access protected
	 * @param array $inputs
	 * @return void
	 */
	protected function _flatten_inputs_data( array $inputs ) {
		foreach ( $inputs as $name => $input ) {
			$include = true;
			foreach ( $this->ignore_regexps as $regex ) {
				if ( preg_match( $regex, $name ) ) {
					$include = false;
				}
			}
			if ( $include ) {
				$this->data[$name] = $input->value;
			}
		}
	}
}